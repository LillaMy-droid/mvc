<?php

namespace App\Card;

use App\Card\DeckOfCards;
use App\Card\CardHand;
use App\Card\Rules;
use App\Card\CardGraphic;

/**
 * Class GamePlay
 *
 * Provides core game logic for Blackjack, including creating a game,
 * formatting cards for display, calculating points, and determining winners.
 */
class GamePlay
{
    private Rules $rules;
    private CardGraphic $graphic;

    public function __construct()
    {
        $this->rules = new Rules();
        $this->graphic = new CardGraphic();
    }

    /**
     * Create a new Blackjack game with a shuffled deck, players, and bank hand.
     *
     * @param int $numPlayers Number of players in the game.
     * @return array [$deck, $players, $bankHand]
     *               - $deck: DeckOfCards instance
     *               - $players: array of CardHand objects for each player
     *               - $bankHand: CardHand object for the bank
     */
    public function createGame(int $numPlayers): array
    {
        $deck = new DeckOfCards();
        $deck->shuffleDeck();

        $players = [];
        for ($i = 0; $i < $numPlayers; $i++) {
            $hand = new CardHand();
            $hand->addCardToHand($deck->drawCard());
            $hand->addCardToHand($deck->drawCard());
            $players[] = $hand;
        }

        $bankHand = new CardHand();
        $bankHand->addCardToHand($deck->drawCard());
        $bankHand->addCardToHand($deck->drawCard());

        return [$deck, $players, $bankHand];
    }

    /**
     * Format a CardHand into an array of strings for display.
     * Each card is represented as "♠ Ace", "♥ 10", etc.
     *
     * @param CardHand $hand The hand to format.
     * @return string[] Array of formatted card strings.
     */
    public function formatCards(CardHand $hand): array
    {
        $cards = [];
        foreach ($hand->getHand() as $card) {
            [$valueGraph, $colorSymbol] = $this->graphic->cardGraphic($card);
            $cards[] = $colorSymbol . " " . $valueGraph;
        }
        return $cards;
    }

    /**
     * Calculate the total points for a given hands.
     *
     * @param CardHand $hand The hand to calculate points for.
     * @return int Total points for the hand.
     */
    public function calculatePoints(CardHand $hand): int
    {
        return $this->rules->points($hand);
    }

    public function bankTurn(CardHand $bankHand, DeckOfCards $deck): void
    {
        $this->rules->bankTurn($bankHand, $deck);
    }

    /**
     * Determine the winner between the bank and a player.
     *
     * @param CardHand $bankHand The bank's hand.
     * @param CardHand $playerHand The player's hand.
     * @return string "Player Wins" or "Bank Wins" based on Blackjack rules.
     */
    public function getWinner(CardHand $bankHand, CardHand $playerHand): string
    {
        return $this->rules->getWinner($bankHand, $playerHand);
    }


    /**
    * Prepare result data for the game:
    * - Bank's cards and points
    * - Players' cards and points
    * - Winner for each player
    *
    * @param array $players Array of CardHand objects for all players.
    * @param CardHand $bankHand Bank's hand.
    * @return array {
    *     bankCards: string[], bankPoints: int,
    *     playerCards: array[], playerPoints: int[],
    *     results: array[] (player name and result)
    * }
    */
    public function getResult(array $players, CardHand $bankHand): array
    {
        $bankCards = $this->formatCards($bankHand);
        $bankPoints = $this->calculatePoints($bankHand);

        $playerCards = [];
        $playerPoints = [];
        $results = [];

        foreach ($players as $index => $hand) {
            $playerCards[] = $this->formatCards($hand);
            $playerPoints[] = $this->calculatePoints($hand);
            $results[] = [
                'player' => 'Spelare ' . ($index + 1),
                'result' => $this->getWinner($bankHand, $hand)
            ];
        }

        return [
            'bankCards' => $bankCards,
            'bankPoints' => $bankPoints,
            'playerCards' => $playerCards,
            'playerPoints' => $playerPoints,
            'results' => $results
        ];
    }
}
