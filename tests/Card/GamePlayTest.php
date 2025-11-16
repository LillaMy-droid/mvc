<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\GamePlay;
use App\Card\CardHand;

class GamePlayTest extends TestCase
{
    public function testCreateGame()
    {
        $game = new GamePlay();
        [$deck, $players, $bankHand] = $game->createGame(2);

        $this->assertCount(2, $players);
        $this->assertInstanceOf(\App\Card\DeckOfCards::class, $deck);
        $this->assertInstanceOf(CardHand::class, $bankHand);
    }

    public function testCardsReturnsStrings()
    {
        $game = new GamePlay();
        $hand = new CardHand();
        $hand->addCardToHand(new \App\Card\Card([1, 'Spade']));

        $formatted = $game->formatCards($hand);
        $this->assertIsArray($formatted);
    }

    public function testGetResultStructure()
    {
        $game = new GamePlay();
        [$deck, $players, $bankHand] = $game->createGame(1);
        $result = $game->getResult($players, $bankHand);

        $this->assertArrayHasKey('bankCards', $result);
        $this->assertArrayHasKey('results', $result);
    }
}
