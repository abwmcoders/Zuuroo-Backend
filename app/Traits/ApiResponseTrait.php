<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    
    protected function successResponse($data = null, string $message = 'Request completed successfully', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'statusCode' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

  
    protected function errorResponse(string $message, int $code = 500): JsonResponse
    {
        return response()->json([
            'status' => false,
            'statusCode' => $code,
            'message' => $message,
            'data' => null
        ], $code);
    }

 
    protected function inputErrorResponse(array $errors = null, string $message = 'Invalid input', int $code = 422): JsonResponse
    {
        return response()->json([
            'status' => false,
            'statusCode' => $code,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

   
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }
}
