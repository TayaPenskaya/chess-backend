<?php


namespace App\Logic\Components\Pieces;


use App\Logic\Components\Board;
use App\Logic\Components\Constants;

abstract class Piece
{

    protected string $name;
    protected string $color;

    public function __construct(string $name, string $color) {
        $this->name = $name;
        $this->color = $color;
    }

    final public function get_name() {
        return ($this->color == Constants::colors[0]) ? strtoupper($this->name) : strtolower($this->name);
    }

    final public function get_color() {
        return $this->color;
    }

    //проверку на то, что from и to на доске будем делать выше
    abstract public function is_valid_move(string $from, string $to, Board $board);

    public function __toString() {
        return $this->get_name();
    }

    protected function exists_piece_on_way($diff, $from, $part, $is_changing_file, $increase_file, $board, $is_changing_rank = 1) : bool {
        $rank = Constants::get_rank($from);
        $file = Constants::get_file($from);
        if ($diff > 0) {
            while ($diff > $part) {
                $diff -= $part;
                $next_file = (!$is_changing_file) ? $file : (($increase_file) ? Constants::next_letter($file) : Constants::prev_letter($file));
                $key = ($is_changing_rank) ? $next_file. ($rank + 1) : $next_file. $rank;
                if (!$board->is_square_empty($key)) {
                    return 0;
                }
            }
            return 1;
        } elseif ($diff < 0) {
            while ($diff < $part) {
                $diff += $part;
                $prev_file = (!$is_changing_file) ? $file : (($increase_file) ? Constants::prev_letter($file) : Constants::next_letter($file));
                $key = ($is_changing_rank) ? $prev_file. ($rank - 1) : $prev_file. $rank;
                if (!$board->is_square_empty($key)) {
                    return 0;
                }
            }
            return 1;
        }
        return 0;
    }
}