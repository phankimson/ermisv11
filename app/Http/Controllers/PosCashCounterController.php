<?php

namespace App\Http\Controllers;

use App\Services\PosTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PosCashCounterController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            // Kiem tra quyen tao/sua chung tu POS.
            $permission = $request->session()->get('per');
            if (!$permission || (!($permission['a'] ?? false) && !($permission['e'] ?? false))) {
                return response()->json(['status' => false, 'message' => trans('messages.you_are_not_permission')], 403);
            }

            // Kiem tra du lieu dau vao cho chung tu thu/chi.
            $request->validate([
                'cash_type' => 'required|in:cash_receipt,cash_payment',
                'total_amount' => 'required|numeric|min:0.01',
                'transaction_date' => 'nullable|date',
                'note' => 'nullable|string',
            ]);

            // Tao giao dich thu/chi tai quay.
            $transaction = PosTransactionService::create((string) $request->input('cash_type'), [
                'transaction_date' => $request->input('transaction_date'),
                'total_amount' => (float) $request->input('total_amount'),
                'items' => [],
                'note' => $request->input('note'),
            ], (string) Auth::id());

            return response()->json(['status' => true, 'message' => trans('pos.messages.saved_cash'), 'data' => $transaction]);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
