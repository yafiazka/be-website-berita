<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Standard success response.
     */
    public static function success(mixed $data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Standard paginated response.
     */
    public static function paginated(mixed $resource, string $message = 'Success'): JsonResponse
    {
        $paginatedData = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginatedData['data'] ?? [],
            'meta' => [
                'current_page' => $paginatedData['meta']['current_page'] ?? 1,
                'last_page' => $paginatedData['meta']['last_page'] ?? 1,
                'per_page' => $paginatedData['meta']['per_page'] ?? 15,
                'total' => $paginatedData['meta']['total'] ?? 0,
            ],
            'links' => $paginatedData['links'] ?? null,
        ]);
    }

    /**
     * Standard error response.
     */
    public static function error(string $message = 'An error occurred', int $statusCode = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
