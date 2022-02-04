<?php

declare(strict_types=1);

namespace Inventory\Alias\Domain;

use Money\Money;

final class PurchasePricePolicy implements PricePolicy
{
    public function calculate(Money $money): Money
    {
        return $money->multiply(0.3);
    }

}
