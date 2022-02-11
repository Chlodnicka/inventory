<?php

namespace Inventory\Product\Domain;

use Inventory\Alias\Application\ProductId;

interface ProductSales
{
    public function save(ProductId $productId, ProductSalesWinner $alias, string $productSalesPolicyType);
}
