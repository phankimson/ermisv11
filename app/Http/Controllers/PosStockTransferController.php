<?php

namespace App\Http\Controllers;

use App\Services\PosTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class PosStockTransferController extends Controller
{
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
                'warehouse_to_id' => 'required|string|different:warehouse_id',
                'items' => 'required',
                'transaction_date' => 'nullable|date',
                'note' => 'nullable|string',
            ]);

            // Parse danh sach hang hoa tu request.
            $items = json_decode((string) $request->input('items'), true);
            if (!is_array($items) || count($items) === 0) {
                throw ValidationException::withMessages(['items' => trans('pos.messages.invalid_items')]);
            }

            // Tao giao dich chuyen kho va cap nhat ton kho 2 dau kho.
            $transaction = PosTransactionService::create('stock_transfer', [
                'warehouse_id' => $request->input('warehouse_id'),
                'warehouse_to_id' => $request->input('warehouse_to_id'),
                'transaction_date' => $request->input('transaction_date'),
                'items' => $items,
                'note' => $request->input('note'),
            ], (string) Auth::id());

            return response()->json(['status' => true, 'message' => trans('pos.messages.saved_stock_transfer'), 'data' => $transaction]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
