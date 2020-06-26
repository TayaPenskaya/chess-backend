<?php


namespace App\Exceptions;


use Exception;

class GameOverException extends Exception
{
    public function __construct($message = "The game is over, start a new one!", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}