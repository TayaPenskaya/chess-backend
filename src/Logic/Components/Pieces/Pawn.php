<?php


namespace App\Logic\Components\Pieces;


use App\Logic\Components\Board;
use App\Logic\Components\Constants;

class Pawn extends Piece
{

    public function __construct($color) {
        parent::__construct('P', $color);
    }

    public function is_valid_move(string $from, string $to,  Board $board) : bool {
        $is_white = ($this->color == Constants::colors[0]) ? 1 : 0;
        $diff = ($is_white) ? ($board->get_value_of_square($to) - $board->get_value_of_square($from))
            : ($board->get_value_of_square($from) - $board->get_value_of_square($to));
        switch ($diff) {
            case 8:
                return ($board->is_square_empty($to)) ? 1 : 0;
            case 16:
                $rank = Constants::get_rank($from);
                $file = Constants::get_file($from);
                if ($board->is_square_empty($to)) {
                    if ($rank == 2 && $board->is_square_empty($file. ($rank+1))) {
                        return 1;
                    } elseif ($rank == 7 && $board->is_square_empty($file. ($rank-1))) {
                        return 1;
                    }
                }
                return 0;
            case 9:
            case 7:
                return (!$board->is_square_empty($to) && $board->get_piece_of_square($to)->get_color() != $this->color) ? 1 : 0;
            default:
                return 0;
        }
    }
}