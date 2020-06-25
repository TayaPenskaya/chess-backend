<?php

namespace App\Controller;

use App\Entity\Game;
use App\Logic\GameLogic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index() : Response {
        return new Response("Hello! Let's play chess!");
    }

    /**
     * @Route("/start-game", methods={"GET"})
     */
    public function start_game() : Response {
        $game = new GameLogic();
        $entity = new Game();
        $entity->set_logic($game);
        $entity->set_board($game->get_board());
        $entity->set_history($game->get_history());
        $entity->set_move_color($game->get_move_color());
        $entity->set_is_end($game->get_is_end());
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        return new Response('Start new game with id '.$entity->get_id());
    }


    /**
     * @Route("/game/{id}", methods={"GET"})
     */
    public function get_game_by_id($id) : Response {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        if ($game->get_is_end()) {
            throw new \Exception('The game is over, start a new one');
        }

        return new Response('Time to play! Color of move: '. $game->get_move_color());
    }

    /**
     * @Route("/game/{id}/history", methods={"GET"})
     */
    public function get_game_history($id) {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        return new Response(($game->get_history()) ? 'History of moves: ' .$game->get_history() : 'No one’s made a move yet..');
    }

    /**
     * @Route("/game/{id}/board", methods={"GET"})
     */
    public function get_board_of_game($id) {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        $board = unserialize($game->get_board());
        $str = implode(', ', array_map(
                function ($v, $k) {
                    return ($v != NULL) ? $k.'='.$v : $k;
                },
                (array)$board,
                array_keys((array)$board)
            )). "\n";

        return new Response('Board: ' . $str);
    }

    /**
     * @Route("/game/{id}/status", methods={"GET"})
     */
    public function get_game_status($id) {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        return new Response(($game->get_is_end()) ? "Game over!" : "Time to play!");
    }

    /**
     * @Route("/game/{id}/move-color", methods={"GET"})
     */
    public function get_cur_move_color($id) {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        if ($game->get_is_end()) {
            throw new \Exception('The game is over, start a new one');
        }

        return new Response('Color of move: '. $game->get_move_color());
    }

    /**
     * @Route("/game/{id}/make-move/{from}/{to}", methods={"GET"})
     */
    public function make_move_with_id($id, $from, $to) : Response {
        $game = $this->getDoctrine()->getRepository(Game::class)->find($id);

        if (!$game) {
            throw $this->createNotFoundException('No game created for this id: '.$id);
        }

        if ($game->get_is_end()) {
            throw new \Exception('The game is over, start a new one');
        }

        $game_logic = $game->get_logic();
        $res = $game_logic->make_move($from, $to);


        $game->set_move_color($game_logic->get_move_color());
        $game->set_is_end($game_logic->get_is_end());
        $game->set_history($game_logic->get_history());
        $game->set_board($game_logic->get_board());
//        if (unserialize($game->get_board()) == $game_logic->get_board()){
//            echo "AHAHAH";
//        }
        $game->set_logic($game_logic);

        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush();
        return new Response($res);
    }
}
