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
    private int $counter;
    private string $tasty_list;
    private string $move_color;
    private bool $is_end;
    private string $key_of_white_king;
    private string $key_of_black_king;

    public function __construct() {
        $this->board = new Board();
        $this->history = '';
        $this->tasty_list = '';
        $this->counter = 1;
        $this->move_color = Constants::colors[0];
        $this->is_end = false;
        $this->key_of_white_king = "e1";
        $this->key_of_black_king = "e8";
    }

    /**
     * @return array
     */
    public function get_board(): array
    {
        return $this->board->get_board();
    }

    /**
     * @return string
     */
    public function get_history(): string
    {
        return $this->history;
    }

    /**
     * @return string
     */
    public function get_tasty_list(): string
    {
        return $this->tasty_list;
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
                                    $this->make_note_in_tasty_list($yum_piece);
                                    $is_eaten = true;
                                }
                                $this->board->remove_piece_from_square($from);
                                $this->board->set_piece_on_square($to, $piece_from);
                                if ($piece_from instanceof King) {
                                    $this->update_king_keys($to);
                                }
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
                    if ($piece->is_valid_move($key, $this->key_of_black_king, $this->board)) {
                        return true;
                    }
                } else {
                    if ($piece->is_valid_move($key, $this->key_of_white_king, $this->board)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function update_king_keys($key) : void {
        if ($this->move_color == Constants::colors[0]) {
            $this->key_of_white_king = $key;
        } else {
            $this->key_of_black_king = $key;
        }
    }

    private function change_move_color() : void {
        $this->move_color = ($this->move_color == Constants::colors[0]) ? Constants::colors[1] : Constants::colors[0];
    }

    private function make_note_in_tasty_list(Piece $piece) : void {
        $this->tasty_list .= $piece. "\n";
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