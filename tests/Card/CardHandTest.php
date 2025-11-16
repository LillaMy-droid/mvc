<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\CardHand;
use App\Card\Card;

class CardHandTest extends TestCase
{
    public function testConstructor()
    {
        $hand = new CardHand();
        $this->assertEmpty($hand->getHand());
    }

    public function testConstructorAddsCards()
    {
        $card1 = new Card(1, 2);
        $card2 = new Card(10, 3);

        $hand = new CardHand([$card1, $card2]);
        $cards = $hand->getHand();

        $this->assertCount(2, $cards);
        $this->assertSame($card1, $cards[0]);
        $this->assertSame($card2, $cards[1]);
    }

    public function testAddCardToHand()
    {
        $hand = new CardHand();
        $card = new Card(5, 4);

        $hand->addCardToHand($card);
        $cards = $hand->getHand();

        $this->assertCount(1, $cards);
        $this->assertSame($card, $cards[0]);
    }
}
