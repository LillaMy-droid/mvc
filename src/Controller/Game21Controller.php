<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\Game;
use App\Card\Bank;
use App\Card\DeckSession;

class Game21Controller extends AbstractController
{
    #[Route("/game/home", name: "game_home")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/doc", name:"game_doc")]
    public function documentation(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/start", name: "game_start")]
    public function start(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffleDeck();

        $game = new Game();
        $bank = new Bank();

        $player = new CardHand();
        $drawCard = $deck->drawCard();
        if ($drawCard !== 0) {
            $player->addCardToHand($drawCard);
        }

        $drawCard = $deck->drawCard();
        if ($drawCard !== 0) {
            $player->addCardToHand($drawCard);
        }

        $session->set('deckOfCards', $deck);
        $session->set('player', $player);
        $session->set('bank', $bank);
        $session->set('game', $game);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/play", name: "game_play")]
    public function play(SessionInterface $session): Response
    {
        $player = $session->get('player');
        $game = $session->get('game');

        $playerCard = [];

        foreach ($player->getHand() as $card) {
            $graphic = new CardGraphic();
            $playerCard[] = $graphic->cardGraphic($card);
        }

        $points = $game->points($player);
        if ($points > 21) {
            return $this->redirectToRoute('game_result');
        }

        return $this->render('game/play.html.twig', [
            'hand' => $playerCard,
            'points' => $points
        ]);
    }

    #[Route("/game/draw", name:"game_draw")]
    public function draw(SessionInterface $session): Response
    {
        $deckSession = new DeckSession();
        $deck = $deckSession->getDeckFromSession($session);
        $player = $session->get('player');
        $game = $session->get('game');

        if (!$player) {
            $player = new CardHand();
            $player->addCardToHand($deck->drawCard());
            $player->addCardToHand($deck->drawCard());
        }

        $card = $deck->drawCard();
        if ($card) {
            $player->addCardToHand($card);
        }

        $session->set('deckOfCards', $deck);
        $session->set('player', $player);

        $points = $game->points($player);
        if ($points > 21) {
            return $this->redirectToRoute('game_bank');
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route("game/bank", name: "game_bank")]
    public function bank(SessionInterface $session): Response
    {

        $deck = $session->get('deckOfCards');
        $bank = $session->get('bank');
        $game = $session->get('game');

        $result = $bank->playTurn($deck, $game);

        $session->set('bank', $bank);
        $session->set('deckOfCards', $deck);

        $session->set('bank_cards', $result['deckOfCards']);
        $session->set('bank_points', $result['points']);


        return $this->redirectToRoute('game_result');
    }
    #[Route("game/result", name:"game_result")]
    public function result(SessionInterface $session)
    {
        $player = $session->get('player');
        $bank = $session->get('bank');
        $game = $session->get('game');
        $bankPoints = $session->get('bank_points');
        $deckSession = new DeckSession();

        $playerCard = $deckSession->getGraphicCard($player);

        $bankCard = $deckSession->getGraphicCard($bank);

        $result = $game->getWinner($bank->getHand(), $player);

        return $this->render('game/result.html.twig', [
            'player' => $playerCard,
            'player_points' => $game->points($player),
            'bank' => $bankCard,
            'bank_points' => $bankPoints,
            'result' => $result
        ]);
    }
}
