<?php

namespace App\Http\Controllers;

use App\Http\Model\PosTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $summary = PosTransaction::get_daily_summary($date);
        $recent = PosTransaction::get_recent_by_date($date, 20);

        return response()->json([
            'status' => true,
            'connection' => $connection,
            'date' => $date,
            'summary' => $summary,
            'recent' => $recent,
        ]);
    }
}
