<?php

namespace App\Card;

class CardHand
{
    private array $hand;

    public function __construct(array $cards = [])
    {
        $this->hand = [];
        foreach ($cards as $card) {
            $this->addCardToHand($card);
        }
    }

    /**
     * Returns all cards in the hand.
     *
     * @return Card[]
     */

    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * Adds a new card to the hand.
     *
     * @param Card $card Card to add.
     * @return void
     */
    public function addCardToHand(Card $card): void
    {
        $this->hand[] = $card;
    }
}
