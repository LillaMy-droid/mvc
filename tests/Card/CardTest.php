<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
    Test cases for class Card and CardGraphic
 */
class CardTest extends TestCase
{
    public function testCard(): void
    {
        $card = new Card();

        $this->assertInstanceOf(\App\Card\Card::class, $card);
    }
    public function testCardValues(): void
    {
        $card = new Card();
        $card->setValue(2);
        $card->setColor(2);

        $this->assertEquals([2,2], $card->getCard());
        $this->assertEquals(2, $card->getValue());
        $this->assertEquals(2, $card->getColor());
    }

    public function testCardGraphic(): void
    {
        $card1 = new Card();
        $card1->setValue(2);
        $card1->setColor(2);

        $card2 = new Card();
        $card2->setValue(1);
        $card2->setColor(1);

        $card3 = new Card();
        $card3->setValue(12);
        $card3->setColor(3);

        $card4 = new Card();
        $card4->setValue(13);
        $card4->setColor(4);

        $card5 = new Card();
        $card5->setValue(11);

        $graphic = new cardGraphic();

        $this->assertEquals(["2", "♥"], $graphic->cardGraphic($card1));
        $this->assertEquals(["Ace", "♠"], $graphic->cardGraphic($card2));
        $this->assertEquals(["Queen", "♦"], $graphic->cardGraphic($card3));
        $this->assertEquals(["King", "♣"], $graphic->cardGraphic($card4));
        $this->assertEquals(["Knight", "?"], $graphic->cardGraphic($card5));

    }

    public function testCardGraphicString(): void
    {
        $card1 = new Card();
        $card1->setValue(2);
        $card1->setColor(2);

        $card2 = new Card();
        $card2->setValue(1);
        $card2->setColor(1);

        $card3 = new Card();
        $card3->setValue(12);
        $card3->setColor(3);

        $card4 = new Card();
        $card4->setValue(13);
        $card4->setColor(4);

        $card5 = new Card();
        $card5->setValue(11);

        $graphic = new cardGraphic();

        $this->assertEquals(["2", "Heart"], $graphic->cardGraphicString($card1));
        $this->assertEquals(["Ace", "Spade"], $graphic->cardGraphicString($card2));
        $this->assertEquals(["Queen", "Diamond"], $graphic->cardGraphicString($card3));
        $this->assertEquals(["King", "Clove"], $graphic->cardGraphicString($card4));
        $this->assertEquals(["Knight", "?"], $graphic->cardGraphicString($card5));
    }
}
