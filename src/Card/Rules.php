<?php

namespace App\Card;

/**
 * Class Rules
 *
 * Implements the Blackjack rules.
 */
class Rules
{
    /**
     * Calculates the total points of the hand.
     *
     * @param CardHand $hand The hand to calculate points for.
     * @return int Total points for the hand.
     */
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

    /**
     * Add a new card to the player's hand by drawing from the deck.
     *
     * @param CardHand $hand The player's hand to add the card to.
     * @param DeckOfCards $deck The deck to draw the card from.
     * @return void
     */
    public function hit(CardHand $hand, DeckOfCards $deck): void
    {
        $card = $deck->drawCard();
        if ($card) {
            $hand->addCardToHand($card);
        }
    }

    /**
     * Execute the bank's turn logic:
     * - The bank draws cards until reaching at least 17 points.
     *
     * @param CardHand $bankHand The bank's hand.
     * @param DeckOfCards $deck The deck to draw cards from.
     * @return void
     */
    public function bankTurn(CardHand $bankHand, DeckOfCards $deck): void
    {
        while ($this->points($bankHand) < 17) {
            $this->hit($bankHand, $deck);
        }
    }


    /**
     * Determine the winner between the bank and a player based on Blackjack rules:
     * - If player exceeds 21, bank wins.
     * - If bank exceeds 21, player wins.
     * - If points are equal, bank wins.
     * - Otherwise, the higher score wins.
     *
     * @param CardHand $bankHand The bank's hand.
     * @param CardHand $playerHand The player's hand.
     * @return string "Player Wins" or "Bank Wins".
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
