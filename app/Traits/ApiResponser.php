<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

trait ApiResponser
{
    public function successResponse($data, $message = null, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ], $code);
    }

    public function errorResponse($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR, $data = null, $exception = null): JsonResponse
    {
        if ($exception instanceof Throwable) {
            Log::error("API Error: " . $exception->getMessage(), [
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
            'error'   => config('app.debug') && $exception ? $exception->getMessage() : null
        ], $code);
    }

    public function validationResponse($errors, $message = 'Los datos proporcionados no son válidos.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $errors,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
