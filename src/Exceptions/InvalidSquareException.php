<?php


namespace App\Exceptions;


use Throwable;

class InvalidSquareException extends \Exception
{
    public function __construct($key, $message = "Trying to move from/to the square outside the board: ", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message . $key . ". \n", $code, $previous);
    }
}