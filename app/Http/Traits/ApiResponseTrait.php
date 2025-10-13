<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait ApiResponseTrait
{
    /**
     * Standard successful API response.
     *
     * @param  mixed  $data  The main data payload.
     * @param  string  $message  A descriptive success message.
     * @param  int  $status  The HTTP status code.
     */
    protected function successResponse(mixed $data, string $message = 'success', int $status = 200): JsonResponse
    {
        if ($data == null) {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $status);
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Standard error API response.
     *
     * @param  string  $message  A descriptive error message.
     * @param  int  $status  The HTTP status code.
     * @param  array  $stacktrace  Optional array for error details/stacktrace.
     */
    protected function errorResponse(string $message, int $status = 400, array $stacktrace = []): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            // Include 'stacktrace' only if it has data
            'stacktrace' => $stacktrace,
        ], $status);
    }

    /**
     * Standard error API response for Validation Errors (HTTP 422).
     *
     * @param  ValidationException  $e  The ValidationException instance.
     */
    protected function validationErrorResponse(ValidationException $e): JsonResponse
    {
        // Extracts the errors from the MessageBag as a simple associative array
        $errors = $e->errors();

        return response()->json([
            'status' => 422, // HTTP 422 Unprocessable Entity is standard for validation errors
            'message' => 'invalid payload',
            // Use 'errors' or 'data' field to return the specific validation messages
            'errors' => $errors,
        ], 422);
    }
}
