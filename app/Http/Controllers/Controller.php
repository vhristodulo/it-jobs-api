<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
 
class Controller extends BaseController
{
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        $statusCode = 400;

        $result = [
            'status' => [
                'success' => false,
                'code' => $statusCode,
                'message' => $errors
            ],
            'data' => []
        ];

        return response($result, $statusCode);
    }
}
