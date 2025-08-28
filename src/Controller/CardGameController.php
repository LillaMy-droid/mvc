<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\CardGraphic;
use App\Card\DeckSession;

class CardGameController extends AbstractController
{
    #[Route("/card/home", name: "card_home")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/session", name:"session_info")]
    public function session(SessionInterface $session): Response
    {
        $sessionData = $session->all();
        return $this->render('/card/session.html.twig', ['sessionData' => $sessionData]);
    }

    #[Route("/card/session/delete", name: "session_delete")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();
        if (count($session->all()) <= 0) {
            $this->addFlash('success', "Session destroyed successfully");
        } elseif (count($session->all()) >= 1) {
            $this->addFlash('error', "Session NOT destroyed successfully");
        }
        return $this->render('/card/session_delete.html.twig');
    }

    #[Route("/card/deck", name: "show_deck")]
    public function showDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->sortDeck();
        $graphCards = new cardGraphic();
        $cards = [];
        foreach ($deck->getDeck() as $card) {
            $cards[] = $graphCards->cardGraphic($card);
        }
        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', []);
        return $this->render('/card/deck.html.twig', ['cards' => $cards]);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $cards = [];

        $deck = new DeckOfCards();
        $deck->shuffleDeck();
        $graphCards = new cardGraphic();

        foreach ($deck->getDeck() as $card) {
            $cards[] = $graphCards->cardGraphic($card);
        }

        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', []);
        return $this->render('/card/shuffle.html.twig', ['cards' => $cards]);
    }

    #[Route("/card/deck/draw", name: "draw_card")]
    public function drawCard(SessionInterface $session): Response
    {
        $deckSession = new DeckSession();
        $info = $deckSession->drawCardsFromDeck($session, 1);

        if ($info[1] === "Deck is now empty") {
            $this->addFlash('error', "Deck is now empty");
        }

        return $this->render('/card/draw.html.twig', [
            'drawnCards' => $info[0],
            'countDeck' => $info[1],
            'cards' => $info[2]
        ]);
    }

    #[Route("card/deck/draw/{num<\d+>}", name: "draw_number")]
    public function drawNumber(int $num, SessionInterface $session): Response
    {

        $deckSession = new DeckSession();
        $info = $deckSession->drawCardsFromDeck($session, 1);

        if ($info[1] === "Deck is now empty") {
            $this->addFlash('error', "Deck is now empty");
        }

        return $this->render('/card/draw.html.twig', [
            'drawnCards' => $info[0],
            'countDeck' => $info[1],
            'cards' => $info[2]
        ]);
    }
}
