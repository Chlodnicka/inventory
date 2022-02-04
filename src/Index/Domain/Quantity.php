<?php

declare(strict_types=1);

namespace Inventory\Index\Domain;

use JetBrains\PhpStorm\Pure;

final class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value < 0 ? 0 : $value;
    }

    public function get(): int
    {
        return $this->value;
    }

    #[Pure] public function equals(Quantity $quantity): bool
    {
        return $this->value === $quantity->get();
    }

    public function subtract(Quantity $quantity): Quantity
    {
        $result = $this->value - $quantity->get();
        if ($result < 0) {
            throw new \Exception('Quantity cannot be less than 0');
        }

        return new Quantity($result);
    }

    public function add(Quantity $quantity): Quantity
    {
        return new Quantity($this->value + $quantity->get());
    }
}
