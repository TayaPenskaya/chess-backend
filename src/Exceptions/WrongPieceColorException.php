<?php


namespace App\Exceptions;


class WrongPieceColorException extends \Exception
{
    public function __construct($color, $message = "I guess you forgot the color of your figures.. Your color - ", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message . $color . ". \n", $code, $previous);
    }
}