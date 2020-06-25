<?php


namespace App\Logic\Components;


abstract class Constants
{

    const colors = ['W', 'B'];
    const letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
    const max_rank = 8;

    public static function next_letter(string $letter) {
        return Constants::letters[array_search($letter, Constants::letters)+1];
    }

    public static function prev_letter(string $letter) {
        return Constants::letters[array_search($letter, Constants::letters)-1];
    }

    public static function get_rank(string $key) {
        return intval(substr($key, -1));
    }

    public static function get_file(string $key) {
        return $key[0];
    }
}