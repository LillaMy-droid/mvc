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

class CardGameControllerJson
{
    #[Route("api/deck")]
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

        $session->set('deck', $deck);
        $session->set('cards', $cards);

        $response = new JsonResponse($sortedDeck);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("api/deck/shuffle")]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $cards = [];

        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $graphCards = new cardGraphic();

        foreach ($deck->getDeck() as $card) {
            $cards[] = $graphCards->cardGraphicString($card);
        }

        $session->set('deck', $deck);
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
        $deck = $session->get('deck');
        $drawnCards = $session->get('drawnCards');
        $graphCards = new cardGraphic();
        $graphicCard = [];
        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
        }
        if (!$drawnCards) {
            $drawnCards = [];
        }
        $cards = $deck->drawMultipleCard($num);
        
        if ($cards === null) {
            $graphicCard = "Deck is now empty";
            $countDeck = $deck->countDeck();
            return new JsonResponse([
                'Cards left:' => $countDeck,
                'Drawn cards:' => $graphicCard
            ]);
        }

        foreach ($cards as $card) {
            $drawnCards[] = $card;
            $graphicCard[] = $graphCards->cardGraphicString($card);
        }

        $session->set('deck', $deck);
        $session->set('drawnCards', $drawnCards);

        $countDeck = $deck->countDeck();
        return new JsonResponse([
            'Cards left:' => $countDeck,
            'Drawn cards:' => $graphicCard
        ]);

    }

    #[Route("/api/deck/draw", name: "deck_draw_one", methods: ["GET"])]
    public function drawCard(SessionInterface $session, int $num = 1): JsonResponse
    {
        $deck = $session->get('deck');
        $drawnCards = $session->get('drawnCards');
        $graphCards = new CardGraphic();
        $graphicCard = [];

        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
        }
        if (!$drawnCards) {
            $drawnCards = [];
        }
        $cards = $deck->drawMultipleCard($num);
       
        if ($cards === null) {
            $graphicCard = "Deck is now empty";
            $countDeck = $deck->countDeck();
            return new JsonResponse([
                'Cards left:' => $countDeck,
                'Drawn cards:' => $graphicCard
            ]);
        }

        foreach ($cards as $card) {
            $drawnCards[] = $card;
            $graphicCard[] = $graphCards->cardGraphicString($card);
        }

        $session->set('deck', $deck);
        $session->set('drawnCards', $drawnCards);

        $countDeck = $deck->countDeck();
        return new JsonResponse([
            'Cards left:' => $countDeck,
            'Drawn cards:' => $graphicCard
        ]);

    }

}
