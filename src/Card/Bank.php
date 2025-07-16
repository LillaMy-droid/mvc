<?php

namespace App\Card;

use App\Card\CardHand;

class Bank
{
    private CardHand $hand;
    public function __construct()
    {
        $this->hand = new CardHand();
    }

    public function getHand(): CardHand
    {
        return $this->hand;
    }

    
    /**
    * @return array{cards: Card[], points: int}
    */
    public function playTurn(DeckOfCards $deck, Game $game): array
    {
        $drawnCards = [];

        while (true) {
            $points = $game->points($this->hand);
            if ($points >= 17) {
                break;
            }

            $card = $deck->drawCard();
            if ($card === null) {
                break;
            }

            $this->hand->addCardToHand($card);
            $drawnCards[] = $card;
        }

        return [
            'cards' => $drawnCards,
            'points' => $game->points($this->hand)
        ];
    }
}
