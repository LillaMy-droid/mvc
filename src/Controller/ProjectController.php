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
    public function start(SessionInterface $session, Request $request): Response
    {
        $num = $request->query->get('num', 2);
        $deck = new DeckOfCards();
        $deck->shuffleDeck();

        $players = [];
        for ($i = 0; $i < $num; $i++) {
            $hand = new CardHand();
            $hand->addCardToHand($deck->drawCard());
            $hand->addCardToHand($deck->drawCard());
            $players[] = $hand;
        }

        $bankHand = new CardHand();
        $bankHand->addCardToHand($deck->drawCard());
        $bankHand->addCardToHand($deck->drawCard());

        $session->set('deck', $deck);
        $session->set('players', $players);
        $session->set('bankHand', $bankHand);
        $session->set('currentPlayerIndex', 0);

        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/game", name: "proj_game", methods: ["GET"])]
    public function game(SessionInterface $session): Response
    {
        $players = $session->get('players');
        $bankHand = $session->get('bankHand');
        $currentPlayerIndex = $session->get('currentPlayerIndex');

        if (!$players || !$bankHand) {
            return $this->redirectToRoute('proj_home');
        }

        $graphic = new CardGraphic();
        $game = new Rules();

        // Bankens kort
        $bankCards = [];
        foreach ($bankHand->getHand() as $card) {
            [$valueGraph, $colorSymbol] = $graphic->cardGraphic($card);
            $bankCards[] = $colorSymbol . " " . $valueGraph;
        }
        $bankPoints = $game->points($bankHand);

        // Spelarnas kort och poäng
        $playerCards = [];
        $playerPoints = [];
        foreach ($players as $hand) {
            $cards = [];
            foreach ($hand->getHand() as $card) {
                [$valueGraph, $colorSymbol] = $graphic->cardGraphic($card);
                $cards[] = $colorSymbol . " " . $valueGraph;
            }
            $playerCards[] = $cards;
            $playerPoints[] = $game->points($hand);
        }

        return $this->render('proj/game.html.twig', [
            'bankCards' => $bankCards,
            'bankPoints' => $bankPoints,
            'playerCards' => $playerCards,
            'playerPoints' => $playerPoints,
            'currentPlayer' => $currentPlayerIndex
        ]);
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
    public function result(SessionInterface $session): Response
    {
        $players = $session->get('players');
        $bankHand = $session->get('bankHand');
        $game = new Rules();

        if (!$players || !$bankHand) {
            return $this->redirectToRoute('proj_home');
        }

        $graphic = new CardGraphic();

        // Bankens kort och poäng
        $bankCards = [];
        foreach ($bankHand->getHand() as $card) {
            [$valueGraph, $colorSymbol] = $graphic->cardGraphic($card);
            $bankCards[] = $colorSymbol . " " . $valueGraph;
        }
        $bankPoints = $game->points($bankHand);

        // Spelarnas kort och poäng
        $playerCards = [];
        $playerPoints = [];
        foreach ($players as $hand) {
            $cards = [];
            foreach ($hand->getHand() as $card) {
                [$valueGraph, $colorSymbol] = $graphic->cardGraphic($card);
                $cards[] = $colorSymbol . " " . $valueGraph;
            }
            $playerCards[] = $cards;
            $playerPoints[] = $game->points($hand);
        }

        // Resultat per spelare
        $results = [];
        foreach ($players as $index => $hand) {
            $results[] = [
                'player' => 'Spelare ' . ($index + 1),
                'result' => $game->getWinner($bankHand, $hand)
            ];
        }

        return $this->render('proj/result.html.twig', [
            'bankCards' => $bankCards,
            'bankPoints' => $bankPoints,
            'playerCards' => $playerCards,
            'playerPoints' => $playerPoints,
            'results' => $results
        ]);
    }

}
