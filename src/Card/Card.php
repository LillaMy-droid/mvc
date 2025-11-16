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

    /**
    * Set the value of the card represented by an integer.
    * @param int$color The int represents the color of the card.
    */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
    * Set the color of the card represented by an integer.
    * @param int$color The int represents the color of the card.
    */
    public function setColor(int $color): void
    {
        $this->color = $color;
    }

    /**
    * Returns value of card as int.
    *
    * @return int[] Returns value of card as int.
    */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
    * Returns color of card as int.
    *
    * @return int[] Returns color of card as int.
    */
    public function getColor(): int
    {
        return $this->color ;
    }
    /**
    * Returns card as an array.
    *
    * @return int[] Array with value and color as integers.
    */
    public function getCard(): array
    {
        $this->getValue();
        $this->getColor();
        $this->card = [$this->value, $this->color];
        return $this->card;
    }
}
