<?php

namespace Inventory\Alias\Domain;

use Inventory\Alias\Application\ProductId;

interface Suppliers
{
    public function getByProductId(ProductId $productId);
}
