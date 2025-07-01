<?php

namespace App\Card;

class Card
{
    protected $value;

    public function __construct()
    {
        $this->value = null;
        $this->color_num = null;
        $this->color = null;
        $this->card = null;
        $this->value_graph = null;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function setColorNum(int $color): void
    {
        $this->color_num = $color;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getColor(): int
    {
        return $this->color_num ;
    }

    public function getCard(): array
    {
        $this->getValue();
        $this->getColor();
        $this->card = [$this->value, $this->color_num];
        return $this->card;
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
