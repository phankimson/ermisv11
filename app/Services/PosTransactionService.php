<?php

namespace App\Services;

use App\Http\Model\HistoryAction;
use App\Http\Model\Menu;
use App\Http\Model\PosInventory;
use App\Http\Model\PosTransaction;
use App\Http\Model\PosTransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class PosTransactionService
{
    public static function create(string $type, array $payload, ?string $cashierId = null): PosTransaction
    {
        $connection = env('CONNECTION_DB_POS', 'mysql3');

        return DB::connection($connection)->transaction(function () use ($type, $payload, $cashierId) {
            $items = collect($payload['items'] ?? []);
            $warehouseId = $payload['warehouse_id'] ?? null;
            $warehouseToId = $payload['warehouse_to_id'] ?? null;
            $transactionDate = $payload['transaction_date'] ?? now()->toDateString();

            if (in_array($type, ['sale', 'return', 'stock_in', 'stock_out'], true) && empty($warehouseId)) {
                throw new InvalidArgumentException(trans('pos.errors.warehouse_required'));
            }

            if ($type === 'stock_transfer' && (empty($warehouseId) || empty($warehouseToId))) {
                throw new InvalidArgumentException(trans('pos.errors.warehouse_transfer_required'));
            }

            if (in_array($type, ['sale', 'return', 'stock_in', 'stock_out', 'stock_transfer'], true) && $items->isEmpty()) {
                throw new InvalidArgumentException(trans('pos.errors.items_required'));
            }

            $totalAmount = $items->sum(function ($item) {
                return (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0);
            });

            if (in_array($type, ['cash_receipt', 'cash_payment'], true)) {
                $totalAmount = (float) ($payload['total_amount'] ?? 0);
            }

            $transaction = PosTransaction::create([
                'code' => self::generateCode($type),
                'type' => $type,
                'transaction_date' => $transactionDate,
                'warehouse_id' => $warehouseId,
                'warehouse_to_id' => $warehouseToId,
                'cashier_id' => $cashierId,
                'total_amount' => $totalAmount,
                'note' => $payload['note'] ?? null,
                'active' => 1,
            ]);

            foreach ($items as $item) {
                $quantity = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $productId = $item['product_id'] ?? null;

                if (empty($productId) || $quantity <= 0) {
                    continue;
                }

                PosTransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $quantity * $unitPrice,
                    'active' => 1,
                ]);

                self::applyStock($type, $warehouseId, $warehouseToId, $productId, $quantity);
            }

            DB::afterCommit(function () use ($transaction, $type, $cashierId, $warehouseId, $warehouseToId, $transactionDate, $items) {
                self::recordHistoryAction($transaction, $type, $cashierId, [
                    'transaction_id' => (string) $transaction->id,
                    'code' => (string) $transaction->code,
                    'type' => $type,
                    'transaction_date' => (string) $transactionDate,
                    'warehouse_id' => $warehouseId,
                    'warehouse_to_id' => $warehouseToId,
                    'total_amount' => (float) $transaction->total_amount,
                    'items_count' => (int) $items->count(),
                ]);
            });

            return $transaction->load('items');
        });
    }

    private static function recordHistoryAction(PosTransaction $transaction, string $type, ?string $userId, array $data): void
    {
        if (empty($userId)) {
            return;
        }

        try {
            HistoryAction::create([
                'type' => 2, // Add
                'user' => $userId,
                'menu' => self::resolveMenuId($type),
                'url' => 'pos/'.str_replace('_', '-', $type),
                'dataz' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ]);
        } catch (Throwable $e) {
            Log::warning('pos.history_action.failed', [
                'transaction_id' => $transaction->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private static function resolveMenuId(string $type): string
    {
        $codes = match ($type) {
            'sale' => ['sale', 'pos-sale', 'pos'],
            'return' => ['return', 'pos-return', 'pos'],
            'stock_in' => ['stock-in', 'pos-stock-in', 'pos'],
            'stock_out' => ['stock-out', 'pos-stock-out', 'pos'],
            'stock_transfer' => ['stock-transfer', 'pos-stock-transfer', 'pos'],
            'cash_receipt' => ['cash-receipt', 'pos-cash-receipt', 'pos'],
            'cash_payment' => ['cash-payment', 'pos-cash-payment', 'pos'],
            default => ['pos'],
        };

        $menu = Menu::query()->whereIn('code', $codes)->where('active', 1)->orderBy('created_at')->first();

        return $menu ? (string) $menu->id : '0';
    }

    private static function applyStock(string $type, ?string $warehouseId, ?string $warehouseToId, string $productId, float $quantity): void
    {
        if ($type === 'sale' || $type === 'stock_out') {
            self::decreaseStock($warehouseId, $productId, $quantity);
            return;
        }

        if ($type === 'return' || $type === 'stock_in') {
            self::increaseStock($warehouseId, $productId, $quantity);
            return;
        }

        if ($type === 'stock_transfer') {
            self::decreaseStock($warehouseId, $productId, $quantity);
            self::increaseStock($warehouseToId, $productId, $quantity);
        }
    }

    private static function decreaseStock(?string $warehouseId, string $productId, float $quantity): void
    {
        $stock = PosInventory::query()
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->first();

        $available = (float) ($stock->quantity ?? 0);
        if ($available < $quantity) {
            throw new InvalidArgumentException(trans('pos.errors.stock_not_enough'));
        }

        $stock->quantity = $available - $quantity;
        $stock->save();
    }

    private static function increaseStock(?string $warehouseId, string $productId, float $quantity): void
    {
        $stock = PosInventory::query()
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->lockForUpdate()
            ->first();

        if (!$stock) {
            PosInventory::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'active' => 1,
            ]);
            return;
        }

        $stock->quantity = (float) $stock->quantity + $quantity;
        $stock->save();
    }

    private static function generateCode(string $type): string
    {
        $prefix = match ($type) {
            'sale' => 'SALE',
            'return' => 'RETN',
            'stock_in' => 'STIN',
            'stock_out' => 'STOT',
            'stock_transfer' => 'STTF',
            'cash_receipt' => 'CSIN',
            'cash_payment' => 'CSOT',
            default => 'POS',
        };

        return sprintf('%s-%s-%s', $prefix, now()->format('YmdHis'), strtoupper(substr((string) str()->uuid(), 0, 6)));
    }
}
