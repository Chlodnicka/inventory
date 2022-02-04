<?php

declare(strict_types=1);

namespace Inventory\Alias\Domain;

use Money\Money;

final class SalesPricePolicy
{
    public function calculate(Money $money): Money
    {
        return $money->multiply(0.4);
    }
}
