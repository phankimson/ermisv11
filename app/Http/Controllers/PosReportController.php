<?php

namespace App\Http\Controllers;

use App\Http\Model\PosTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosReportController extends Controller
{
    public function daily(Request $request): JsonResponse
    {
        $permission = $request->session()->get('per');
        if (!$permission || !($permission['v'] ?? false)) {
            return response()->json(['status' => false, 'message' => trans('messages.you_are_not_permission')], 403);
        }

        $date = $request->input('date', now()->toDateString());
        $connection = env('CONNECTION_DB_POS', 'mysql3');

        $summary = PosTransaction::query()
            ->select('type', DB::raw('COUNT(*) as total_docs'), DB::raw('SUM(total_amount) as total_amount'))
            ->whereDate('transaction_date', $date)
            ->groupBy('type')
            ->orderBy('type')
            ->get();

        $recent = PosTransaction::query()
            ->with(['warehouse:id,name', 'warehouseTo:id,name'])
            ->whereDate('transaction_date', $date)
            ->latest('created_at')
            ->limit(20)
            ->get();

        return response()->json([
            'status' => true,
            'connection' => $connection,
            'date' => $date,
            'summary' => $summary,
            'recent' => $recent,
        ]);
    }
}
