<?php


namespace App\Logic;


use App\Exceptions\EmptySquareException;
use App\Exceptions\InedibleKingException;
use App\Exceptions\InvalidSquareException;
use App\Exceptions\WrongMoveException;
use App\Exceptions\WrongPieceColorException;
use App\Logic\Components\Board;
use App\Logic\Components\Constants;
use App\Logic\Components\Pieces\King;
use App\Logic\Components\Pieces\Piece;
use Exception;

class GameLogic
{

    private Board $board;
    private string $history;
    private int $counter = 1;
    private string $move_color;
    private bool $is_end;

    public function __construct(Board $board, $move_color, $is_end, $history) {
        $this->board = $board;
        $this->move_color = $move_color;
        $this->is_end = $is_end;
        $this->history = $history;
    }

    /**
     * @return Board
     */
    public function get_board(): Board
    {
        return $this->board;
    }

    /**
     * @return string
     */
    public function get_history(): string
    {
        return $this->history;
    }

    /**
     * @return bool
     */
    public function get_is_end()
    {
        return $this->is_end;
    }

    /**
     * @return string
     */
    public function get_move_color(): string
    {
        return $this->move_color;
    }

    public static function init() {
        return new GameLogic(new Board(), Constants::colors[0], false, '');
    }

    public function make_move(string $from, string $to) : string {
        try {
            if ($this->board->is_inside_board($from)) {
                if ($this->board->is_inside_board($to)) {
                    if (!$this->board->is_square_empty($from)) {
                        $piece_from = $this->board->get_piece_of_square($from);
                        if ($piece_from->get_color() == $this->move_color) {
                            if ($piece_from->is_valid_move($from, $to, $this->board)) {
                                $is_eaten = false;
                                if (!$this->board->is_square_empty($to)) {
                                    $yum_piece = $this->board->get_piece_of_square($to);
                                    if ($yum_piece instanceof King) {
                                        throw new InedibleKingException();
                                    }
                                    $is_eaten = true;
                                }
                                $this->board->remove_piece_from_square($from);
                                $this->board->set_piece_on_square($to, $piece_from);
                                $this->make_note_in_history($from, $to, $piece_from, $is_eaten);
                                $this->change_move_color();
                                if ($this->is_king_at_gunpoint()) {
                                    $this->is_end = true;
                                }
                                return "Good move";
                            }
                            throw new WrongMoveException($piece_from);
                        }
                        throw new WrongPieceColorException($this->move_color);
                    }
                    throw new EmptySquareException($to);
                }
                throw new InvalidSquareException($to);
            }
            throw new InvalidSquareException($from);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function is_king_at_gunpoint() : bool {
        foreach (array_keys($this->board->get_board()) as $key) {
            if (!$this->board->is_square_empty($key)) {
                $piece = $this->board->get_piece_of_square($key);
                if ($piece->get_color() == Constants::colors[0]) {
                    $black_king_key = $this->find_black_king();
                    if ($piece->is_valid_move($key, $black_king_key, $this->board)) {
                        return true;
                    }
                } else {
                    $white_king_key = $this->find_white_king();
                    if ($piece->is_valid_move($key, $white_king_key, $this->board)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function find_black_king() : string {
        foreach (array_keys($this->board->get_board()) as $key) {
            if (!$this->board->is_square_empty($key)) {
                $piece = $this->board->get_piece_of_square($key);
                if ($piece instanceof King && $piece->get_color() == Constants::colors[1]) {
                    return $key;
                }
            }
        }
        return '';
    }

    private function find_white_king() : string {
        foreach (array_keys($this->board->get_board()) as $key) {
            if (!$this->board->is_square_empty($key)) {
                $piece = $this->board->get_piece_of_square($key);
                if ($piece instanceof King && $piece->get_color() == Constants::colors[0]) {
                    return $key;
                }
            }
        }
        return '';
    }

    private function change_move_color() : void {
        $this->move_color = ($this->move_color == Constants::colors[0]) ? Constants::colors[1] : Constants::colors[0];
    }

    private function make_note_in_history(string $from, string $to, Piece $piece, bool $is_eaten) : void {
        if ($piece->get_color() == Constants::colors[0]){
            $this->history .= $this->counter. ". ";
        }
        $this->history .= ($is_eaten) ? $piece. $from. "x". $to. " " : $piece. $from. "--". $to. " ";
        if ($piece->get_color() == Constants::colors[1]) {
            $this->counter++;
            $this->history .= "\n";
        }
    }
}