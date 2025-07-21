<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
    Test cases for class DeckOfCards
 */
class DeckTest extends TestCase
{
    public function testInit()
    {
        $deck = new DeckOfCards();
        $this->assertCount(52, $deck->getDeck());
        $this->assertInstanceOf(DeckOfCards::class, $deck);
    }

    public function testCountDeck()
    {
        $deck = new DeckOfCards();
        $deck2 = new DeckOfCards();
        $deck2->drawCard();

        $this->assertEquals(52, $deck->countDeck());
        $this->assertEquals(51, $deck2->countDeck());
    }

    public function testDrawCard()
    {
        $deck = new DeckOfCards();

        $this->assertInstanceOf(Card::class, $deck->drawCard());
        $cards = $deck->drawMultipleCard(51);

        $this->assertNull($deck->drawCard());
    }

    public function testDrawMultipleCards()
    {
        $deck = new DeckOfCards();
        $cards = $deck->drawMultipleCard(5);

        $this->assertCount(5, $cards);
        $this->assertCount(47, $deck->getDeck());

        $deck2 = new DeckOfCards();
        $cards = $deck2->drawMultipleCard(60);

        $this->assertCount(52, $cards);
        $this->assertCount(0, $deck2->getDeck());
    }

    public function testSorted()
    {
        $sorted = new DeckOfCards();
        $deck = new DeckOfCards();
        $sorted->sortDeck();

        $this->assertCount(52, $sorted->getDeck());
        $this->assertNotEquals($deck, $sorted);

        $deck->shuffleDeck();
        $this->assertNotEquals($deck, $sorted);
    }

    public function testDrawnCards()
    {
        $deck = new DeckOfCards();

        $drawn = $deck->drawMultipleCard(2);
        $this->assertCount(2, $deck->getDrawnCards());
        $this->assertEquals($drawn, $deck->getDrawnCards());
        $this->assertCount(50, $deck->getDeck());
    }
}
