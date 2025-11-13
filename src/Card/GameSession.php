<?php

namespace App\Card;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Class GameSession
 *
 * Handles storing and retrieving game-related data in the Symfony session.
 * This includes the deck, players, bank hand, and the current player's index.
 */
class GameSession
{

    /**
     * Save the current game state into the session.
     *
     * @param SessionInterface $session The Symfony session instance.
     * @param DeckOfCards $deck The deck of cards used in the game.
     * @param array $players Array of CardHand objects representing players' hands.
     * @param CardHand $bankHand The bank's hand.
     * @return void
     */
    public function saveGame(SessionInterface $session, $deck, $players, $bankHand): void
    {
        $session->set('deck', $deck);
        $session->set('players', $players);
        $session->set('bankHand', $bankHand);
        $session->set('currentPlayerIndex', 0);
    }

    /**
     * Retrieve the current game state from the session.
     *
     * @param SessionInterface $session The Symfony session instance.
     * @return array [$deck, $players, $bankHand, $currentPlayerIndex]
     *               - $deck: DeckOfCards instance
     *               - $players: array of CardHand objects
     *               - $bankHand: CardHand object
     *               - $currentPlayerIndex: int
     */
    public function getGame(SessionInterface $session): array
    {
        return [
            $session->get('deck'),
            $session->get('players'),
            $session->get('bankHand'),
            $session->get('currentPlayerIndex')
        ];
    }


    /**
     * Update the players' hands in the session.
     *
     * @param SessionInterface $session The Symfony session instance.
     * @param array $players Array of CardHand objects representing players' hands.
     * @return void
     */
    public function updatePlayers(SessionInterface $session, $players): void
    {
        $session->set('players', $players);
    }


    /**
     * Update the bank's hand in the session.
     *
     * @param SessionInterface $session The Symfony session instance.
     * @param CardHand $bankHand The bank's hand.
     * @return void
     */
    public function updateBank(SessionInterface $session, $bankHand): void
    {
        $session->set('bankHand', $bankHand);
    }


    /**
     * Update the current player's index in the session.
     *
     * @param SessionInterface $session The Symfony session instance.
     * @param int $index The index of the current player.
     * @return void
     */
    public function updateCurrentPlayer(SessionInterface $session, int $index): void
    {
        $session->set('currentPlayerIndex', $index);
    }
}
