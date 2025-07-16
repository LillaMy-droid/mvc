<?php

namespace App\Card;

use App\Card\CardHand;

class Game
{
    public function __construct()
    {
    }

    public function points(CardHand $hand): int
    {
        $totalPoints = 0;
        $ace = 0;

        foreach ($hand->getHand() as $card) {
            $value = $card->getValue();
            if ($value === 1) {
                $ace++;
                $totalPoints += 14;
            }
            $totalPoints += $value;
        }
        while ($totalPoints > 21 && $ace > 0) {
            $totalPoints -= 13;
            $ace--;
        }
        return $totalPoints;
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
