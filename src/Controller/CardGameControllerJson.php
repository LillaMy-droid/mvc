<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\CardGraphic;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\Game;
use App\Card\DeckSession;

class CardGameControllerJson
{
    #[Route("api/deck", name: "api_deck")]
    public function jsonDeck(SessionInterface $session): Response
    {
        $sortedDeck = [];
        $deck = new DeckOfCards();
        $cards = $deck->getDeck();
        $graphCards = new cardGraphic();
        foreach ($cards as $card) {
            $sortedDeck[] = $graphCards->cardGraphicString($card);
        }

        usort($sortedDeck, function ($cardA, $cardB) {
            return $cardA[1] <=> $cardB[1];
        });

        $session->set('deckOfCards', $deck);
        $session->set('cards', $cards);

        $response = new JsonResponse($sortedDeck);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/deck/shuffle", name:"api_shuffle")]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $cards = [];

        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $graphCards = new cardGraphic();

        foreach ($deck->getDeck() as $card) {
            $cards[] = $graphCards->cardGraphicString($card);
        }

        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', []);
        $response = new JsonResponse($cards);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", name: "deck_draw_number", methods: ["GET", "POST"])]
    public function drawCards(SessionInterface $session, int $num = 1): JsonResponse
    {
        $deckSession = new DeckSession();
        $info = $deckSession->drawCardsFromDeck($session, $num);

        return new JsonResponse([
            'Cards left:' => $$info[1],
            'Drawn cards:' => $info[0]
        ]);
    }

    #[Route("/api/deck/draw", name: "deck_draw_one", methods: ["GET"])]
    public function drawCard(SessionInterface $session, int $num = 1): JsonResponse
    {
        $deckSession = new DeckSession();
        $info = $deckSession->drawCardsFromDeck($session, $num);

        return new JsonResponse([
            'Cards left:' => $$info[1],
            'Drawn cards:' => $info[0]
        ]);

    }
    #[Route("api/game/", name:"api_game_21")]
    public function game21(SessionInterface $session): Response
    {
        $player = $session->get('player') ?? [];
        $bank = $session->get('bank') ?? [];
        $game = $session->get('game') ?? new Game();
        $bankPoints = $session->get('bank_points') ?? 0;
        $deck = $session->get('deckOfCards') ?? new DeckOfCards();

        $playerCard = [];
        $bankCard = [];
        $graphic = new CardGraphic();
        $playerPoint = $game->points($player);

        foreach ($player->getHand() as $card) {
            $graphic = new CardGraphic();
            $playerCard[] = $graphic->cardGraphicString($card);
        }
        foreach ($bank->getHand()->getHand() as $card) {
            $bankCard[] = $graphic->cardGraphicString($card);
        }

        return new JsonResponse([
            'Player:' => $playerCard,
            'PlayerPoints:' => $playerPoint,
            'Bank:' => $bankCard,
            'Bank points:' => $bankPoints,
            'Deck: ' => $deck
        ]);
    }

}
