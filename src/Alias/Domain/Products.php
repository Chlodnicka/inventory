<?php

namespace Inventory\Alias\Domain;

use Inventory\Alias\Application\ProductId;
use Inventory\Index\Domain\IndexId;

interface Products
{
    public function getProductIdByIndex(int $supplierId, IndexId $indexId): ProductId;
}
