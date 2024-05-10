<?php

namespace App\Http\Exceptions;

use Exception;


class BasicException extends Exception
{
    protected $data;

    public function __construct($code, $message = '', $data = null, Exception $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
