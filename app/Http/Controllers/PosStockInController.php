<?php

namespace App\Http\Controllers;

use App\Services\PosTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class PosStockInController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $permission = $request->session()->get('per');
            if (!$permission || (!($permission['a'] ?? false) && !($permission['e'] ?? false))) {
                return response()->json(['status' => false, 'message' => trans('messages.you_are_not_permission')], 403);
            }

            $request->validate([
                'warehouse_id' => 'required|string',
                'items' => 'required',
                'transaction_date' => 'nullable|date',
                'note' => 'nullable|string',
            ]);

            $items = json_decode((string) $request->input('items'), true);
            if (!is_array($items) || count($items) === 0) {
                throw ValidationException::withMessages(['items' => 'Danh sách hàng hóa không hợp lệ.']);
            }

            $transaction = PosTransactionService::create('stock_in', [
                'warehouse_id' => $request->input('warehouse_id'),
                'transaction_date' => $request->input('transaction_date'),
                'items' => $items,
                'note' => $request->input('note'),
            ], (string) Auth::id());

            return response()->json(['status' => true, 'message' => 'Đã lưu phiếu nhập kho.', 'data' => $transaction]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
