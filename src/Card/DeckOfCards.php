<?php

namespace App\Card;

use App\Card\Card;

class DeckOfCards extends Card
{
    protected $deck = [];
    protected $drawnCards = [];

    public function __construct()
    {

        for ($value = 1; $value <= 13; $value++) {
            for ($color = 1; $color <= 4; $color++) {
                $card = new Card();
                $card->setValue($value);
                $card->setColor($color);
                $this->deck[] = $card;
            }
        }
    }

    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    public function sortDeck(): array
    {

        usort($this->deck, function ($cardA, $cardB) {
            if ($cardA->getColor() === $cardB->getColor()) {
                return $cardA->getValue() <=> $cardB->getValue();
            }
            return $cardA->getColor() <=> $cardB->getColor();

        });

        return $this->deck;
    }

    public function drawCard(): ?Card
    {
        if (empty($this->deck)) {
            return null;
        }
        $card = array_shift($this->deck);
        $this->drawnCards[] = $card;
        return $card;
    }

    public function drawMultipleCard(int $num): array
    {
        $cards = [];
        for ($i = 0; $i < $num; $i++) {
            $card = $this->drawCard();
            if ($card === null) {
                break;
            }
            $cards[] = $card;
        }
        return $cards;
    }

    public function getDeck(): array
    {
        return $this->deck;
    }

    public function getDrawnCards(): array
    {
        return $this->drawnCards;
    }

    public function countDeck(): int
    {
        return count($this->deck);
    }
}
