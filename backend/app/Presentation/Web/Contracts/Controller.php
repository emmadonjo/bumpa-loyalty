<?php

namespace App\Presentation\Web\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    /**
     * Handles JSON success response
     * @param array<string, mixed>|array<int, mixed>|object $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function apiSuccess(
        array|object $data = [],
        string $message = '',
        int $code =  Response::HTTP_OK,
        array $headers = [],
    ) : JsonResponse {
        if ($data instanceof ResourceCollection) {
            ResourceCollection::wrap('data');
            return $data
                ->additional([
                    'status' => true,
                    'message' => $message
                ])
                ->response()->setStatusCode($code)
                ->withHeaders($headers);
        }
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code)
            ->withHeaders($headers);
    }

    /**
     * Handles JSON error response
     * @param array<string, mixed>|array<int, mixed>|object $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function apiError(
        array|object $data = [],
        string $message = '',
        int $code =  Response::HTTP_BAD_REQUEST
    ) : JsonResponse {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
