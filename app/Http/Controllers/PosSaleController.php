<?php

namespace App\Http\Controllers;

use App\Http\Model\PosProduct;
use App\Services\PosTransactionService;
use App\Services\ViettelEinvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class PosSaleController extends Controller
{
    public function __construct(
        protected ViettelEinvoiceService $viettelEinvoiceService
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Kiem tra quyen tao/sua chung tu POS.
            $permission = $request->session()->get('per');
            if (!$permission || (!($permission['a'] ?? false) && !($permission['e'] ?? false))) {
                return response()->json(['status' => false, 'message' => trans('messages.you_are_not_permission')], 403);
            }

            // Kiem tra du lieu dau vao.
            $request->validate([
                'warehouse_id' => 'required|string',
                'items' => 'required',
                'transaction_date' => 'nullable|date',
                'note' => 'nullable|string',
            ]);

            // Parse danh sach hang hoa tu request.
            $items = json_decode((string) $request->input('items'), true);
            if (!is_array($items) || count($items) === 0) {
                throw ValidationException::withMessages(['items' => trans('pos.messages.invalid_items')]);
            }

            // Tao giao dich ban hang va cap nhat ton kho.
            $transaction = PosTransactionService::create('sale', [
                'warehouse_id' => $request->input('warehouse_id'),
                'transaction_date' => $request->input('transaction_date'),
                'items' => $items,
                'note' => $request->input('note'),
            ], (string) Auth::id());

            // Day hoa don dien tu len Viettel neu cau hinh duoc bat.
            $einvoice = $this->publishSaleInvoice($transaction, $items);

            return response()->json([
                'status' => true,
                'message' => trans('pos.messages.saved_sale'),
                'data' => $transaction,
                'einvoice' => $einvoice,
            ]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    private function publishSaleInvoice($transaction, array $items): array
    {
        // Bo qua neu tinh nang Viettel eInvoice chua bat.
        if (
            !$this->viettelEinvoiceService->isEnabled() ||
            !$this->viettelEinvoiceService->shouldPublishOnSale()
        ) {
            return [
                'status' => 'skipped',
                'message' => 'Viettel eInvoice is disabled for sale publish.',
            ];
        }

        try {
            // Lay ten san pham theo product_id de map len hoa don.
            $products = PosProduct::query()
                ->whereIn('id', collect($items)->pluck('product_id')->filter()->all())
                ->get(['id', 'name'])
                ->keyBy('id');

            // Chuan hoa item theo cau truc API Viettel.
            $invoiceItems = collect($items)->map(function ($item) use ($products) {
                $productId = (string) ($item['product_id'] ?? '');
                $quantity = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);

                return [
                    'product_id' => $productId,
                    'name' => (string) optional($products->get($productId))->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $quantity * $unitPrice,
                ];
            })->values()->all();

            // Goi service publish hoa don.
            $result = $this->viettelEinvoiceService->publishSaleInvoice([
                'transaction_code' => (string) $transaction->code,
                'transaction_date' => (string) $transaction->transaction_date,
                'buyer' => [],
                'items' => $invoiceItems,
                'total_amount' => (float) $transaction->total_amount,
                'note' => (string) ($transaction->note ?? ''),
                'metadata' => [
                    'transaction_id' => (string) $transaction->id,
                    'type' => 'sale',
                ],
            ]);

            // Ghi log thanh cong de theo doi.
            Log::info('pos.viettel_einvoice.publish_sale.success', [
                'transaction_id' => $transaction->id,
                'transaction_code' => $transaction->code,
                'response' => $result['raw'] ?? [],
            ]);

            // Luu tom tat trang thai hoa don vao ghi chu chung tu.
            $this->appendEinvoiceSummaryToTransaction(
                $transaction,
                'success',
                (string) ($result['invoice_no'] ?? ''),
                (string) ($result['lookup_code'] ?? '')
            );

            return [
                'status' => ($result['status'] ?? true) ? 'success' : 'failed',
                'message' => (string) ($result['message'] ?? 'Publish sale invoice success.'),
                'invoice_no' => $result['invoice_no'] ?? null,
                'invoice_url' => $result['invoice_url'] ?? null,
                'lookup_code' => $result['lookup_code'] ?? null,
            ];
        } catch (Throwable $e) {
            // Ghi log loi va danh dau trang thai failed.
            Log::error('pos.viettel_einvoice.publish_sale.failed', [
                'transaction_id' => $transaction->id,
                'transaction_code' => $transaction->code,
                'error' => $e->getMessage(),
            ]);

            $this->appendEinvoiceSummaryToTransaction($transaction, 'failed', '', '');

            return [
                'status' => 'failed',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function appendEinvoiceSummaryToTransaction($transaction, string $status, string $invoiceNo, string $lookupCode): void
    {
        // Tao chuoi tom tat trang thai eInvoice de luu vao note.
        $summary = '[EINV:'.strtoupper($status);
        if ($invoiceNo !== '') {
            $summary .= '|NO:'.$invoiceNo;
        }
        if ($lookupCode !== '') {
            $summary .= '|LOOKUP:'.$lookupCode;
        }
        $summary .= ']';

        // Noi vao note cu va gioi han do dai 500 ky tu.
        $note = trim(((string) $transaction->note).' '.$summary);
        $transaction->note = mb_substr($note, 0, 500);
        $transaction->save();
    }
}
