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

class CardGameController extends AbstractController
{
    #[Route("/card/home", name: "card_home")]
    public function home(SessionInterface $session): Response
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
        if (empty($session->all())) {
            $this->addFlash('success', "Session destroyed successfully");
        } else {
            $this->addFlash('error', "Session NOT destroyed successfully");
        }
        return $this->render('/card/session_delete.html.twig');
    }

    #[Route("/card/deck", name: "show_deck")]
    public function showDeck(SessionInterface $session): Response
    {
        return $this->render('/card/deck.html.twig');
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $cards = [];

        $deck = new DeckOfCards();
        $deck->shuffleDeck();

        foreach ($deck->getDeck() as $card) {
            $cards[] = $deck->cardGraphic($card);
        }

        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', []);
        return $this->render('/card/shuffle.html.twig', ['cards' => $cards]);
    }

    #[Route("/card/deck/draw", name: "draw_card")]
    public function drawCard(SessionInterface $session): Response
    {
        $deck = $session->get('deckOfCards');
        $drawnCards = $session->get('drawnCards');
        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
        }
        if (!$drawnCards) {
            $drawnCards = [];
        }
        $card = $deck->drawCard();
        if ($card === null) {
            $this->addFlash('error', "Deck is now empty");
        } else {
            $drawnCards[] = $card;
            $graph = $deck->cardGraphic($card);


            $session->set('deckOfCards', $deck);
            $session->set('drawnCards', $drawnCards);
        }

        $countDeck = $deck->countDeck();
        return $this->render('/card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'countDeck' => $countDeck,
            'cards' => $graph
        ]);
    }

    #[Route("card/deck/draw/{num<\d+>}", name: "draw_number")]
    public function draw_number(int $num, SessionInterface $session): Response
    {
        $deck = $session->get('deckOfCards');
        $drawnCards = $session->get('drawnCards');
        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
        }
        if (!$drawnCards) {
            $drawnCards = [];
        }
        $cards = $deck->drawMultipleCard($num);
        if ($cards === null) {
            $this->addFlash('error', "Deck is now empty");
        } else {
            foreach ($cards as $card) {
                $drawnCards[] = $card;
                $graphicCard[] = $deck->cardGraphic($card);
            }

            $session->set('deckOfCards', $deck);
            $session->set('drawnCards', $drawnCards);
        }

        $countDeck = $deck->countDeck();
        return $this->render('/card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'countDeck' => $countDeck,
            'cards' => $graphicCard
        ]);
    }
}
