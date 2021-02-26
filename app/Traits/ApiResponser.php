<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Trait ApiResponser
 * @package App\Traits
 */
trait ApiResponser
{
    /**
     * Returns the payload of a successful response
     * @param array $data
     * @param string $code
     * @return JsonResponse
     */
    public function successResponse(array $data = [], string $code = Response::HTTP_OK) : JsonResponse
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Returns the payload of an error response
     * @param string $error
     * @param string $code
     * @return JsonResponse
     */
    public function errorResponse(string $error, string $code) : JsonResponse
    {
        return response()->json(['error' => $error, 'code' => $code], $code);
    }
}
