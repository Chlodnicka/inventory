<?php

declare(strict_types=1);

namespace Inventory\Product\Domain;

use Inventory\Alias\Application\ProductId;

interface ProductSalesPolicy
{
    public function supports(ProductId $productId): bool;

    public function calculate(ProductId $productId): ProductSalesWinner;

    public function getType(): string;
}
