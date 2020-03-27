<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Format error response 
     * {
     *      "error": "error description",
     *      "data": [List of error detail]
     * }
     */
    public function responseError($errorMsg, $errors, $httpCode = 400) {
        return response()->json(['error' => $errorMsg, 'data' => $errors], $httpCode);
    }

    /**
     * Format success response 
     * {
     *      "error": "error description",
     *      "data": [List of error detail]
     * }
     */
    public function responseSuccess($data, $httpCode = 200){
        return response()->json($data, $httpCode);
    }
}
