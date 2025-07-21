<?php

namespace App\Card;

class Card
{
    protected int $value;
    protected int $color;
    protected array $card;

    public function __construct()
    {
        $this->value = 0;
        $this->color = 0;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function setColor(int $color): void
    {
        $this->color = $color;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getColor(): int
    {
        return $this->color ;
    }

    public function getCard(): array
    {
        $this->getValue();
        $this->getColor();
        $this->card = [$this->value, $this->color];
        return $this->card;
    }
}
