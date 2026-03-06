<?php

namespace App\Http\Controllers;

use App\Http\Model\Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Throwable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function handleControllerException(
        Throwable $e,
        int $type = 9,
        int $menuId = 0,
        ?string $url = null,
        ?string $actionName = null,
        string $messageKey = 'messages.error'
    ): JsonResponse {
        $action = $actionName ?? __FUNCTION__;

        Error::create([
            'type' => $type,
            'user_id' => Auth::id(),
            'menu_id' => $menuId,
            'error' => $action . ': ' . $e->getMessage() . ' - Line ' . $e->getLine(),
            'url' => $url ?? '',
            'check' => 0,
        ]);

        return response()->json([
            'status' => false,
            'message' => trans($messageKey),
        ]);
    }
}
