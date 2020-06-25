<?php


namespace App\Logic\Components\Pieces;


use App\Logic\Components\Board;
use App\Logic\Components\Constants;

class Rook extends Piece
{

    public function __construct($color) {
        parent::__construct('R', $color);
    }

    public function is_valid_move(string $from, string $to, Board $board) : bool {
        if ($board->is_square_empty($to) || !($board->get_piece_of_square($to)->get_color() == $this->color)) {
            $diff = $board->get_value_of_square($to) - $board->get_value_of_square($from);
            if ($diff % 8 == 0) {
                if ($this->exists_piece_on_way($diff, $from, 8, 0, 0, $board)) {
                    return 1;
                }
            } else {
                $rank_from = Constants::get_rank($from);
                $rank_to = Constants::get_rank($to);
                if ($rank_from == $rank_to) {
                    return ($this->exists_piece_on_way($diff, $from, 1, 1, 1, $board, 0)) ? 1 : 0;
                }
            }
        }
        return 0;
    }
}