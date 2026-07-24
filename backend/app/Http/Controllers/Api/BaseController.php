<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
 use Illuminate\Database\Eloquent\Model;

class BaseController
{
    /**
     * Success Response
     */
    protected function sendSuccess($data = null, $message = 'Success', $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Error Response
     */
    protected function sendError($error = null, $message = 'Error', $code = 400, $errorMessages = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($error !== null) {
            $response['error'] = $error;
        }

        if ($errorMessages !== null) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
