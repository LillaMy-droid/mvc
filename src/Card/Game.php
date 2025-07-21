<?php

namespace App\Card;

use App\Card\CardHand;

/**
    Class has methods to play the game of 21.
 */
class Game
{
    public function __construct()
    {
    }

    /**
    * Calculates th epoint of the current cardhand.
    *
    * @param CardHand $hand The current hand of player
    * @return int $totalPoints The total points of the hand
    */

    public function points(CardHand $hand): int
    {
        $totalPoints = 0;
        $ace = 0;

        foreach ($hand->getHand() as $card) {
            $value = $card->getValue();
            if ($value === 1) {
                $ace++;
                $value = 14;
            }
            $totalPoints += $value;
        }
        while ($totalPoints > 21 && $ace > 0) {
            $totalPoints -= 13;
            $ace--;
        }
        return $totalPoints;
    }

    /**
    * Compares points of both players to see if bank or player won the round.
    *
    * @param CardHand $bankHand Current hand of cards from bank
    * @param CardHand $playerHand Current hand of cards from player
    * @return string Return a string of who the winner is
     */

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
