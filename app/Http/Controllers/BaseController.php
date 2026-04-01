<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    use AuthorizesRequests;

    protected function success(
        string $message,
        mixed $data = null,
        int $status = 200
    ): JsonResponse {
        $response = ['success' => true, 'message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    protected function created(string $message, mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, 201);
    }

    protected function error(
        string $message,
        \Throwable $e = null,
        int $status = 500
    ): JsonResponse {
        $response = ['success' => false, 'message' => $message];

        if ($e !== null && config('app.debug')) {
            $response['error'] = $e->getMessage();
        }

        return response()->json($response, $status);
    }
}
