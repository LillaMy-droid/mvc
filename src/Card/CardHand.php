<?php

namespace App\Card;

class CardHand extends Card
{
    protected $number_of_cards;
    public $hand = [];

    public function __construct($cards)
    {
        parent::__construct();
        $this->number_of_cards = $cards;
    }
    // public function getHand($this->number_of_cards);
    // {
    // }

}
