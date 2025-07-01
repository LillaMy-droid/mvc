<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cardGraphic(Card $card): array
    {
        $value = $card->getValue();
        $suit = $card->getColor();

        switch ($value) {
            case 1: $valueGraph = "Ace";
                break;
            case 12: $valueGraph = "Queen";
                break;
            case 13: $valueGraph = "King";
                break;
            case 11: $valueGraph = "Knight";
                break;
            default: $valueGraph = (string)$value;
        }

        switch ($suit) {
            case 1: $color = "♠";
                break;
            case 2: $color = "♥";
                break;
            case 3: $color = "♦";
                break;
            case 4: $color = "♣";
                break;
            default: $color = "?";
        }

        return [$valueGraph, $color];
    }
    public function cardGraphicString(Card $card): array
    {
        $value = $card->getValue();
        $suit = $card->getColor();

        switch ($value) {
            case 1: $valueGraph = "Ace";
                break;
            case 12: $valueGraph = "Queen";
                break;
            case 13: $valueGraph = "King";
                break;
            case 11: $valueGraph = "Knight";
                break;
            default: $valueGraph = (string)$value;
        }

        switch ($suit) {
            case 1: $color = "Spade";
                break;
            case 2: $color = "Heart";
                break;
            case 3: $color = "Diamond";
                break;
            case 4: $color = "Clove";
                break;
            default: $color = "?";
        }

        return [$valueGraph, $color];
    }

}
