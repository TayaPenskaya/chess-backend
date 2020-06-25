<?php


namespace App\Exceptions;


use Throwable;

class EmptySquareException extends \Exception
{
    public function __construct($key, $message = "Unfortunately, there is no figure on this square: ", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message . $key . ". \n", $code, $previous);
    }
}