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
        if (empty($session->all())) {
            $this->addFlash('success', "Session destroyed successfully");
        } elseif (!empty($session->all())) {
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
        $deck = $session->get('deckOfCards');
        $drawnCards = $session->get('drawnCards');
        $graph = [];
        if (!$deck) {
            $deck = new DeckOfCards();
            $deck->shuffleDeck();
        }
        if (!$drawnCards) {
            $drawnCards = [];
        }
        $graphCards = new cardGraphic();
        $card = $deck->drawCard();
        if ($card === null) {
            $this->addFlash('error', "Deck is now empty");
        } elseif (!$card === null) {
            $drawnCards[] = $card;
            $graph = $graphCards->cardGraphic($card);

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
    public function drawNumber(int $num, SessionInterface $session): Response
    {

        $deck = $session->get('deckOfCards') ?? new DeckOfCards();
        $drawnCards = $session->get('drawnCards') ?? [];

        if (!$session->get('deckOfCards')) {
            $deck->shuffleDeck();
        }

        $cards = $deck->drawMultipleCard($num);
        $graphCards = new CardGraphic();
        $graphicCard = [];
        $countDeck = $deck->countDeck();

        if ($cards === null) {
            $this->addFlash('error', "Deck is now empty");

            return $this->render('/card/draw.html.twig', [
                'drawnCards' => $drawnCards,
                'countDeck' => $countDeck,
                'cards' => $graphicCard
            ]);
        }

        foreach ($cards as $card) {
            $drawnCards[] = $card;
            $graphicCard[] = $graphCards->cardGraphic($card);
        }
        $countDeck = $deck->countDeck();

        $session->set('deckOfCards', $deck);
        $session->set('drawnCards', $drawnCards);

        return $this->render('/card/draw.html.twig', [
            'drawnCards' => $drawnCards,
            'countDeck' => $countDeck,
            'cards' => $graphicCard
        ]);
    }

}
