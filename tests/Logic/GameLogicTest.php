<?php


namespace App\Tests\Logic;


use App\Logic\GameLogic;
use PHPUnit\Framework\TestCase;

class GameLogicTest extends TestCase
{
    public function test_make_move() {
        $game = new GameLogic();

        //pawn 2 squares
        $this->assertEquals("Good move", $game->make_move("d2", "d4"));
        //wrong color
        $this->assertNotEquals("Good move", $game->make_move("e2", "e5"));
        //knight wrong move
        $this->assertNotEquals("Good move",$game->make_move("g8", "g6"));
        //knight
        $this->assertEquals("Good move",$game->make_move("g8", "f6"));
        //bishop
        $this->assertEquals("Good move",$game->make_move("c1", "g5"));
        //pawn
        $this->assertEquals("Good move",$game->make_move("e7", "e5"));
        //bishop eats
        $this->assertEquals("Good move",$game->make_move("g5", "f6"));
        //pawn goes back to eat -
        $this->assertNotEquals("Good move",$game->make_move("e5", "f6"));
        //pawn eats
        $this->assertEquals("Good move",$game->make_move("e5", "d4"));
        //queen eats
        $this->assertEquals("Good move",$game->make_move("d1", "d4"));
        //queen eats
        $this->assertEquals("Good move",$game->make_move("d8", "f6"));
        //knight
        $this->assertEquals("Good move",$game->make_move("b1", "c3"));
        //bishop
        $this->assertEquals("Good move",$game->make_move("f8", "c5"));
        //rook
        $this->assertEquals("Good move",$game->make_move("a1", "d1"));
        //queen
        $this->assertEquals("Good move",$game->make_move("f6", "b6"));
        //pawn
        $this->assertEquals("Good move",$game->make_move("e2", "e3"));
        //king
        $this->assertEquals("Good move",$game->make_move("e8", "d8"));
        //king
        $this->assertEquals("Good move",$game->make_move("e1", "e2"));
        //pawn
        $this->assertEquals("Good move",$game->make_move("h7", "h5"));
        //queen puts the king’s shah
        $this->assertEquals("Good move",$game->make_move("d4", "e5"));
        $this->assertEquals(true, $game->get_is_end());
    }
}