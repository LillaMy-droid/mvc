<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;

class DeckSession
{
    public function getDeckFromSession(SessionInterface $session): DeckOfCards
    {
        $deck = $session->get('deckOfCards');
        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
            $session->set('deckOfCards', $deck);
        }
        return $deck;
    }

    public function getDrawnCardsFromSession(SessionInterface $session): array
    {
        $drawnCards = $session->get('drawnCards') ?? [];
        $session->set('drawnCards', $drawnCards);
        return $drawnCards;
    }

    public function drawCardsFromDeck(SessionInterface $session, int $num): array
    {
        $deck = $this->getDeckFromSession($session);
        $drawnCards = $this->getDrawnCardsFromSession($session);
        $graphicCard = [];
        $graphCards = new cardGraphic();

        $cards = $deck->drawMultipleCard($num);
        if ($cards === null) {
            return [ $graphicCard = "Deck is now empty",
            $countDeck = $deck->countDeck()];
        }
        foreach ($cards as $card) {
            $drawnCards[] = $card;
            $graphicCard[] = $graphCards->cardGraphicString($card);
        }
        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', $drawnCards);
        return [$graphicCard, $countDeck = $deck->countDeck(), $drawnCards];
    }
}
