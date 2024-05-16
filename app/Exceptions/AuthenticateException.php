<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class AuthenticateException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        $message = json_encode($this->getMessage(), JSON_UNESCAPED_UNICODE);
        return response()->json([
            'code' => $this->getCode(),
            'message' => $message,
        ]);
    }
}
