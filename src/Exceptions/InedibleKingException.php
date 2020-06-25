<?php


namespace App\Exceptions;


use Throwable;

class InedibleKingException extends \Exception
{
    public function __construct($message = "You can't eat kings!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}