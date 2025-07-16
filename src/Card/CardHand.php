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
    public function getHand(): array
    {
        return $this->hand;
    }
    public function addCardToHand(Card $card): void
    {
        $this->hand[] = $card;
    }
}
