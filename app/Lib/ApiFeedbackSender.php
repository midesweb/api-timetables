<?php

namespace App\Lib;

use Illuminate\Http\JsonResponse;

trait ApiFeedbackSender
{
    public function sendSuccess(string $message, $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public function sendError(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
