<?php

namespace App\Tests\Card;

use PHPUnit\Framework\TestCase;
use App\Card\GameSession;
use App\Card\DeckOfCards;
use App\Card\CardHand;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameSessionTest extends TestCase
{
    private $sessionMock;
    private $gameSession;

    protected function setUp(): void
    {
        $this->sessionMock = $this->createMock(SessionInterface::class);
        $this->gameSession = new GameSession();
    }

    public function testGetGameSession()
    {
        $deck = new DeckOfCards();
        $players = [new CardHand()];
        $bankHand = new CardHand();

        $this->sessionMock->method('get')
            ->willReturnMap([
                ['deck', $deck],
                ['players', $players],
                ['bankHand', $bankHand],
                ['currentPlayerIndex', 1]
            ]);

        [$retrievedDeck, $retrievedPlayers, $retrievedBank, $index] = $this->gameSession->getGame($this->sessionMock);

        $this->assertSame($deck, $retrievedDeck);
        $this->assertSame($players, $retrievedPlayers);
        $this->assertSame($bankHand, $retrievedBank);
        $this->assertEquals(1, $index);
    }

    public function testUpdatePlayersSession()
    {
        $players = [new CardHand(), new CardHand()];

        $this->sessionMock->expects($this->once())
            ->method('set')
            ->with('players', $players);

        $this->gameSession->updatePlayers($this->sessionMock, $players);
    }

    public function testUpdateBank()
    {
        $bankHand = new CardHand();

        $this->sessionMock->expects($this->once())
            ->method('set')
            ->with('bankHand', $bankHand);

        $this->gameSession->updateBank($this->sessionMock, $bankHand);
    }

    public function testUpdateCurrentPlayer()
    {
        $this->sessionMock->expects($this->once())
            ->method('set')
            ->with('currentPlayerIndex', 2);

        $this->gameSession->updateCurrentPlayer($this->sessionMock, 2);
    }
}
