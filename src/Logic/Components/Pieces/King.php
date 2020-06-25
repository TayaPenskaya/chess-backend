<?php


namespace App\Logic\Components\Pieces;


use App\Logic\Components\Board;

class King extends Piece
{

    public function __construct($color) {
        parent::__construct('K', $color);
    }

    public function is_valid_move(string $from, string $to, Board $board) : bool {
        if ($board->is_square_empty($to) || !($board->get_piece_of_square($to)->get_color() == $this->color)) {
            $diff = abs($board->get_value_of_square($to) - $board->get_value_of_square($from));
            switch ($diff) {
                case 1:
                case 8:
                    return 1;
                default:
                    break;
            }
        }
        return 0;
    }
}