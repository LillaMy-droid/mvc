<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
        foreach ($cards as $card) {
            $sortedDeck[] = $deck->cardGraphicString($card);
        }

        usort($sortedDeck, function ($a, $b) {
            return $a[1] <=> $b[1];
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

        foreach ($deck->getDeck() as $card) {
            $cards[] = $deck->cardGraphicString($card);
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
        } else {
            foreach ($cards as $card) {
                $drawnCards[] = $card;
                $graphicCard[] = $deck->cardGraphicString($card);
            }

            $session->set('deck', $deck);
            $session->set('drawnCards', $drawnCards);
        }

        $countDeck = $deck->countDeck();
        $response = [
            'Cards left:' => $countDeck,
            'Drawn cards:' => $graphicCard
        ];

        return new JsonResponse($response);
    }

    #[Route("/api/deck/draw", name: "deck_draw_one", methods: ["GET"])]
    public function drawCard(SessionInterface $session, int $num = 1): JsonResponse
    {
        $deck = $session->get('deck');
        $drawnCards = $session->get('drawnCards');
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
        } else {
            foreach ($cards as $card) {
                $drawnCards[] = $card;
                $graphicCard[] = $deck->cardGraphicString($card);
            }

            $session->set('deck', $deck);
            $session->set('drawnCards', $drawnCards);
        }

        $countDeck = $deck->countDeck();
        $response = [
            'Cards left:' => $countDeck,
            'Drawn cards:' => $graphicCard
        ];

        return new JsonResponse($response);
    }

}
