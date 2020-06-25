<?php


namespace App\Logic\Components\Pieces;


use App\Logic\Components\Board;

class Bishop extends Piece
{

    public function __construct($color) {
        parent::__construct('B', $color);
    }

    public function is_valid_move(string $from, string $to, Board $board) : bool {
        if ($board->is_square_empty($to) || !($board->get_piece_of_square($to)->get_color() == $this->color)) {
            $diff = $board->get_value_of_square($to) - $board->get_value_of_square($from);
            switch ($diff) {
                case ($diff % 7 == 0):
                    return ($this->exists_piece_on_way($diff, $from, 7, 1, 0, $board)) ? 1 : 0;
                case ($diff % 9 == 0):
                    return ($this->exists_piece_on_way($diff, $from, 9, 1, 1, $board)) ? 1 : 0;
                default:
                    break;
            }
        }
        return 0;
    }
}