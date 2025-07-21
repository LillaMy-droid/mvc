<?php

namespace App\Card;

use App\Card\CardHand;

/**
 *  This class contains method needed for the bank to play
 *  the game of 21.
 */
class Bank
{
    private CardHand $hand;
    public function __construct()
    {
        $this->hand = new CardHand();
    }

    /**
     * Returns the hand of the bank
     * @return CardHand The current hand of the bank
     */
    public function getHand(): CardHand
    {
        return $this->hand;
    }

    /**
    * If points are less than 17 the bank will draw a new card.
    * Card is added to drawn cards to keep track and then return
    * drawn cards and the final points for the bank.
    *
    * @param DeckOfCards $deck The deck to draw cards from
    * @param Game $game The game instance used to calculate points
    * @return array{cards: Card[], points: int} An array with drawn cards and final points
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
