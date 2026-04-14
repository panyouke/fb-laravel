<?php

namespace App\Exceptions;

use Exception;

class ResponseException extends Exception
{
    protected $code;
    protected $message;

    public function __construct($code = 400,$message = '')
    {
        parent::__construct($message, $code);
    }
}
