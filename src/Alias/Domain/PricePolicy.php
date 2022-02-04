<?php

namespace Inventory\Alias\Domain;

use Money\Money;

interface PricePolicy
{
    public function calculate(Money $money): Money;
}
