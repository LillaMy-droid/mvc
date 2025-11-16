<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Rules;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class RulesTest extends TestCase
{
    public function testPointsAceCard()
    {
        $hand = new CardHand();
        $card = new Card();
        $card->setValue(1);
        $card->setColor(2);
        $hand->addCardToHand($card);

        $rules = new Rules();
        $points = $rules->points($hand);

        $this->assertEquals(11, $points);
    }

    public function testPointsFaceCard()
    {
        $hand = new CardHand();
        $card1 = new Card();
        $card1->setValue(12);
        $card1->setColor(2);
        $card2 = new Card();
        $card2->setValue(11);
        $card2->setColor(2);
        $hand->addCardToHand($card1);
        $hand->addCardToHand($card2);

        $rules = new Rules();
        $points = $rules->points($hand);

        $this->assertEquals(20, $points);
    }

    public function testBankTurnDrawsUntil17()
    {
        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $bankHand = new CardHand();
        $card = new Card();
        $card->setValue(2);
        $card->setColor(3);

        $bankHand->addCardToHand($card);

        $rules = new Rules();
        $rules->bankTurn($bankHand, $deck);

        $this->assertGreaterThanOrEqual(17, $rules->points($bankHand));
    }

    public function testGetWinnerPlayerWins()
    {
        $card1 = new Card();
        $card1->setValue(10);
        $card1->setColor(2);
        $card2 = new Card();
        $card2->setValue(9);
        $card2->setColor(3);

        $bankHand = new CardHand();
        $bankHand->addCardToHand($card1);
        $bankHand->addCardToHand($card2);

        $card3 = new Card();
        $card3->setValue(10);
        $card3->setColor(1);
        $card4 = new Card();
        $card4->setValue(10);
        $card4->setColor(4);
        $playerHand = new CardHand();
        $playerHand->addCardToHand($card3);
        $playerHand->addCardToHand($card4);


        $rules = new Rules();
        $result = $rules->getWinner($bankHand, $playerHand);

        $this->assertEquals("Player Wins", $result);
    }
    
    public function testGetWinnerBankWins()
    {
        $card1 = new Card();
        $card1->setValue(10);
        $card1->setColor(2);
        $card2 = new Card();
        $card2->setValue(9);
        $card2->setColor(3);

        $playerHand = new CardHand();
        $playerHand->addCardToHand($card1);
        $playerHand->addCardToHand($card2);

        $card3 = new Card();
        $card3->setValue(10);
        $card3->setColor(1);
        $card4 = new Card();
        $card4->setValue(10);
        $card4->setColor(4);

        $bankHand = new CardHand();
        $bankHand->addCardToHand($card3);
        $bankHand->addCardToHand($card4);

        $rules = new Rules();
        $result = $rules->getWinner($bankHand, $playerHand);

        $this->assertEquals("Bank Wins", $result);
    }

    public function testGetWinnerEqualPoints()
    {
        $card1 = new Card();
        $card1->setValue(9);
        $card1->setColor(2);
        $card2 = new Card();
        $card2->setValue(9);
        $card2->setColor(3);

        $playerHand = new CardHand();
        $playerHand->addCardToHand($card1);

        $bankHand = new CardHand();
        $bankHand->addCardToHand($card2);

        $rules = new Rules();
        $result = $rules->getWinner($bankHand, $playerHand);

        $this->assertEquals("Bank Wins", $result);
    }
}
