<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Game;
use App\Card\Bank;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
    Test cases for class Bank
 */
class BankTest extends TestCase
{
    public function testClass(): void
    {
        $bank = new Bank();

        $this->assertInstanceOf(\App\Card\Bank::class, $bank);
        $this->assertCount(0, $bank->getHand()->getHand());
    }

    public function testPlayTurn(): void
    {
        $deck = new DeckOfCards();
        $game = new Game();
        $bank = new Bank();
        $deck->sortDeck();

        $this->assertEquals(19, $bank->playTurn($deck, $game)['points']);
    }

    public function testGetHand(): void
    {
        $bank = new Bank();

        $hand = $bank->getHand();

        $this->assertInstanceOf(CardHand::class, $hand);
        $this->assertCount(0, $hand->getHand());

    }

    public function testEmptyDeck(): void
    {
        $deck = $this->createMock(DeckOfCards::class);
        $deck->method('drawCard')->willReturn(0);

        $game = new Game();
        $bank = new Bank();

        $result = $bank->playTurn($deck, $game);

        $this->assertEquals(0, $result['points']);
        $this->assertEmpty($result['cards']);
    }


    public function testStop17Points(): void
    {
        $deck = new DeckOfCards();
        $game = $this->createMock(Game::class);
        $game->method('points')->willReturn(17);

        $bank = new Bank();
        $result = $bank->playTurn($deck, $game);

        $this->assertEmpty($result['cards']);
        $this->assertEquals(17, $result['points']);
    }
}
