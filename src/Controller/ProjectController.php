<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\Rules;
use App\Card\CardGraphic;
use App\Card\DeckSession;
use App\Card\GameSession;
use App\Card\GamePlay;

class ProjectController extends AbstractController
{
    #[Route("/proj/home", name: "proj_home")]
    public function home(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/start", name: "proj_start", methods: ["GET"])]
    public function start(SessionInterface $session, Request $request, GamePlay $gamePlay, GameSession $gameSession): Response
    {
        $num = $request->query->get('num', 1);
        [$deck, $players, $bankHand] = $gamePlay->createGame($num);
        $gameSession->saveGame($session, $deck, $players, $bankHand);

        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/game", name: "proj_game", methods: ["GET"])]
    public function game(SessionInterface $session, GamePlay $gamePlay, GameSession $gameSession): Response
    {
        [$deck, $players, $bankHand, $currentPlayerIndex] = $gameSession->getGame($session);

        $bankCards = $gamePlay->formatCards($bankHand);
        $bankPoints = $gamePlay->calculatePoints($bankHand);

        $playerCards = [];
        $playerPoints = [];
        foreach ($players as $hand) {
            $playerCards[] = $gamePlay->formatCards($hand);
            $playerPoints[] = $gamePlay->calculatePoints($hand);
        }

        return $this->render('proj/game.html.twig', compact('bankCards', 'bankPoints', 'playerCards', 'playerPoints', 'currentPlayerIndex'));
    }

    #[Route("/proj/hit", name: "proj_hit", methods: ["POST"])]
    public function hit(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        $players = $session->get('players');
        $currentPlayerIndex = $session->get('currentPlayerIndex');

        $game = new Rules();
        $game->hit($players[$currentPlayerIndex], $deck);

        $session->set('players', $players);

        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/stand", name: "proj_stand", methods: ["POST"])]
    public function stand(SessionInterface $session): Response
    {
        $currentPlayerIndex = $session->get('currentPlayerIndex');
        $players = $session->get('players');

        if ($currentPlayerIndex < count($players) - 1) {
            $session->set('currentPlayerIndex', $currentPlayerIndex + 1);
        } else {
            $deck = $session->get('deck');
            $bankHand = $session->get('bankHand');
            $game = new Rules();
            $game->bankTurn($bankHand, $deck);
            $session->set('bankHand', $bankHand);

            return $this->redirectToRoute('proj_result');
        }

        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/result", name: "proj_result", methods: ["GET"])]
    public function result(SessionInterface $session, GamePlay $gamePlay, GameSession $gameSession): Response
    {
        [$deck, $players, $bankHand] = $gameSession->getGame($session);

        $data = $gamePlay->getResult($players, $bankHand);

        return $this->render('proj/result.html.twig', $data);
    }
}
