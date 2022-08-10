<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidationException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render()
    {
        return response()->json([], 403);
    }
}
