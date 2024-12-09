<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;

class ApiBaseController extends Controller
{
    /**
     * Return succeeded response.
     * 
     * @param mixed $result
     * @param mixed $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function succeeded($result = [], $message = "", $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Return failed response.
     * 
     * @param ?mixed $error
     * @param mixed $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    protected function failed($error = null, $errorMessages = [], $code = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'data' => $errorMessages,
            'message' => $error ?? __('messages.general.something_went_wrong'),
        ];

        return response()->json($response, $code);
    }
}
