<?php


namespace App\Logic\Components;


use App\Logic\Components\Pieces\Bishop;
use App\Logic\Components\Pieces\King;
use App\Logic\Components\Pieces\Knight;
use App\Logic\Components\Pieces\Pawn;
use App\Logic\Components\Pieces\Piece;
use App\Logic\Components\Pieces\Queen;
use App\Logic\Components\Pieces\Rook;

class Board
{

    private array $squares;
    private array $board;

    public function __construct() {
        $this->squares = $this->get_squares();
        $this->clear_board();
        $this->fill_board();
    }

    /**
     * @return array
     */
    public function get_board(): array
    {
        return $this->board;
    }

    public function remove_piece_from_square(string $key) : void {
        unset($this->board[$key]);
    }

    public function set_piece_on_square(string $key, Piece $piece) :void {
        $this->board[$key] = $piece;
    }

    public function is_inside_board(string $key) : bool {
        return array_key_exists($key, $this->squares);
    }

    public function is_square_empty(string $key) : bool {
        return !isset($this->board[$key]);
    }

    public function get_piece_of_square(string $key) : Piece {
        return $this->board[$key];
    }

    public function get_value_of_square(string $key) : int {
        return $this->squares[$key];
    }

    public function __toString() {
        return implode(', ', array_map(
                function ($v, $k) {
                    return ($v != NULL) ? $k.'='.$v : $k;
                },
                $this->board,
                array_keys($this->board)
            )). "\n";
    }

    private function get_squares() {
        $arr = array();
        $counter = 0;
        for ($rank = 1; $rank <= Constants::max_rank; $rank++) {
            foreach (Constants::letters as $letter) {
                $key = $letter. $rank;
                $arr[$key] = $counter;
                $counter++;
            }
        }
        return $arr;
    }

    private function clear_board() {
        $this->board = array();
    }

    private function fill_board() {
        $this->board = array_fill_keys(array_keys($this->squares), NULL);
        for ($rank = 2; $rank <= Constants::max_rank; $rank += 5) {
            foreach (Constants::letters as $letter) {
                $key = $letter. $rank;
                $this->board[$key] = ($rank == 2) ? new Pawn(Constants::colors[0]) : new Pawn(Constants::colors[1]);
            }
        }
        for ($rank = 1; $rank <= Constants::max_rank; $rank += 7) {
            $main_line = ($rank == 1) ? $this->get_main_line(Constants::colors[0]) : $this->get_main_line(Constants::colors[1]);
            for ($i = 0; $i < Constants::max_rank; $i++) {
                $key = Constants::letters[$i]. $rank;
                $this->board[$key] = $main_line[$i];
            }
        }
    }

    private function get_main_line($color) {
        return [
            new Rook($color),
            new Knight($color),
            new Bishop($color),
            new Queen($color),
            new King($color),
            new Bishop($color),
            new Knight($color),
            new Rook($color),
        ];
    }
}