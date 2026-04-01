<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function success(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) $response['data'] = $data;

        return response()->json($response, $status);
    }

    protected function created(mixed $data = null, string $message = ''): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function error(string $message, int $status = 500, mixed $errors = null): JsonResponse
    {
        $response = ['success' => false, 'message' => $message];
        if ($errors !== null) $response['errors'] = $errors;

        return response()->json($response, $status);
    }

    protected function forbidden(string $message = 'Bu işlem için yetkiniz yok.'): JsonResponse
    {
        return $this->error($message, 403);
    }

    protected function notFound(string $message = 'Kayıt bulunamadı.'): JsonResponse
    {
        return $this->error($message, 404);
    }
}
