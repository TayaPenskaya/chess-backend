<?php


namespace App\Exceptions;


class WrongMoveException extends \Exception
{
    public function __construct($piece, $message = "This figure can’t walk like this. Your figure - ", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message . $piece . ". \n", $code, $previous);
    }
}