<?php

declare(strict_types=1);

namespace Inventory\Product\Domain;

use Money\Money;

final class ProductSalesWinner
{
    private int $supplierId;
    private Money $price;
    private int $deliveryDays;
}
