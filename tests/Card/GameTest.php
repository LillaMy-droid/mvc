<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\Game;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

/**
    Test cases for class Game
 */
class GameTest extends TestCase
{
    public function testCreateGame()
    {
        $game = new Game();
        $this->assertInstanceOf(\App\Card\Game::class, $game);
    }

    public function testPoints()
    {
        $hand = new CardHand();
        $card1 = new Card();
        $card2 = new Card();
        $game = new Game();

        $card1->setValue(8);
        $card1->setColor(1);
        $hand->addCardToHand($card1);
        $card2->setValue(2);
        $card2->setColor(1);
        $hand->addCardToHand($card2);

        $points = $game->points($hand);

        $this->assertEquals(10, $points);
    }

    public function testPointsAce()
    {

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $game = new Game();

        $card1->setValue(1);
        $card1->setColor(1);

        $card2->setValue(1);
        $card2->setColor(2);

        $card3->setValue(1);
        $card3->setColor(3);

        $hand = new CardHand([$card1, $card2, $card3]);

        $points = $game->points($hand);

        $this->assertEquals(16, $points);
    }

    public function testGetWinnerEqualPoints()
    {
        $player = new CardHand();
        $bank = new CardHand();
        $game = new Game();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();

        $card1->setValue(8);
        $card1->setColor(1);
        $player->addCardToHand($card1);
        $card2->setValue(2);
        $card2->setColor(1);
        $player->addCardToHand($card2);

        $card3->setValue(8);
        $card3->setColor(1);
        $bank->addCardToHand($card3);
        $card4->setValue(2);
        $card4->setColor(1);
        $bank->addCardToHand($card4);

        $this->assertEquals("Bank Wins", $game->getWinner($bank, $player));
    }

    public function testGetWinnerBank()
    {
        $player = new CardHand();
        $bank = new CardHand();
        $game = new Game();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();

        $card1->setValue(8);
        $card1->setColor(1);
        $player->addCardToHand($card1);
        $card2->setValue(2);
        $card2->setColor(1);
        $player->addCardToHand($card2);

        $card3->setValue(10);
        $card3->setColor(1);
        $bank->addCardToHand($card3);
        $card4->setValue(2);
        $card4->setColor(1);
        $bank->addCardToHand($card4);

        $this->assertEquals("Bank Wins", $game->getWinner($bank, $player));
    }

    public function testGetWinnerPlayer()
    {
        $player = new CardHand();
        $bank = new CardHand();
        $game = new Game();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();

        $card1->setValue(8);
        $card1->setColor(1);
        $player->addCardToHand($card1);
        $card2->setValue(2);
        $card2->setColor(1);
        $player->addCardToHand($card2);

        $card3->setValue(6);
        $card3->setColor(1);
        $bank->addCardToHand($card3);
        $card4->setValue(2);
        $card4->setColor(1);
        $bank->addCardToHand($card4);

        $this->assertEquals("Player Wins", $game->getWinner($bank, $player));
    }

    public function testGetWinnerAbove21()
    {
        $player = new CardHand();
        $bank = new CardHand();
        $game = new Game();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();

        $card1->setValue(13);
        $card1->setColor(1);
        $player->addCardToHand($card1);
        $card2->setValue(13);
        $card2->setColor(1);
        $player->addCardToHand($card2);

        $card3->setValue(13);
        $card3->setColor(1);
        $bank->addCardToHand($card3);
        $card4->setValue(13);
        $card4->setColor(1);
        $bank->addCardToHand($card4);

        $this->assertEquals("Bank Wins", $game->getWinner($bank, $player));
    }
    public function testGetWinnerBankAbove21()
    {
        $player = new CardHand();
        $bank = new CardHand();
        $game = new Game();

        $card1 = new Card();
        $card2 = new Card();
        $card3 = new Card();
        $card4 = new Card();

        $card1->setValue(1);
        $card1->setColor(1);
        $player->addCardToHand($card1);
        $card2->setValue(13);
        $card2->setColor(2);
        $player->addCardToHand($card2);

        $card3->setValue(13);
        $card3->setColor(3);
        $bank->addCardToHand($card3);
        $card4->setValue(13);
        $card4->setColor(4);
        $bank->addCardToHand($card4);

        $this->assertEquals("Player Wins", $game->getWinner($bank, $player));
    }
}
