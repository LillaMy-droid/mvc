<?php

namespace App\Card;

class Rules
{
    public function points(CardHand $hand): int
    {
        $totalPoints = 0;
        $ace = 0;

        foreach ($hand->getHand() as $card) {
            $value = $card->getValue();
            if ($value === 1) {
                $ace++;
                $value = 11;
            } elseif ($value >= 11) {
                $value = 10;
            }
            $totalPoints += $value;
        }

        while ($totalPoints > 21 && $ace > 0) {
            $totalPoints -= 10;
            $ace--;
        }

        return $totalPoints;
    }

    public function hit(CardHand $hand, DeckOfCards $deck): void
    {
        $card = $deck->drawCard();
        if ($card) {
            $hand->addCardToHand($card);
        }
    }

    public function bankTurn(CardHand $bankHand, DeckOfCards $deck): void
    {
        while ($this->points($bankHand) < 17) {
            $this->hit($bankHand, $deck);
        }
    }

    public function getWinner(CardHand $bankHand, CardHand $playerHand): string
    {
        $bank = $this->points($bankHand);
        $player = $this->points($playerHand);

        if ($player > 21) {
            return "Bank Wins";
        }
        if ($bank > 21) {
            return "Player Wins";
        }
        if ($bank === $player) {
            return "Bank Wins";
        }
        if ($player > $bank) {
            return "Player Wins";
        }
        return "Bank Wins";
    }
}
