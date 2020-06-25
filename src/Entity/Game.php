<?php

namespace App\Entity;

use App\Logic\Components\Board;
use App\Logic\GameLogic;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $board;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $history;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $move_color;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_end;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $logic;

    public function get_id(): ?int
    {
        return $this->id;
    }

    public function get_board(): ?string
    {
        return $this->board;
    }

    public function set_board(array $board): self
    {
        $this->board = serialize($board);

        return $this;
    }

    public function get_history(): ?string
    {
        return $this->history;
    }

    public function set_history(string $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function get_move_color(): ?string
    {
        return $this->move_color;
    }

    public function set_move_color(string $move_color): self
    {
        $this->move_color = $move_color;

        return $this;
    }

    public function get_is_end(): ?bool
    {
        return $this->is_end;
    }

    public function set_is_end(bool $is_end): self
    {
        $this->is_end = $is_end;

        return $this;
    }

    public function get_logic(): ?GameLogic
    {
        $logic = unserialize($this->logic);
        return ($logic instanceof GameLogic) ? $logic : new GameLogic();
    }

    public function set_logic(GameLogic $logic): self
    {
        $this->board = serialize($logic);

        return $this;
    }
}
