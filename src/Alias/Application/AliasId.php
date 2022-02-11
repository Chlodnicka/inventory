<?php

declare(strict_types=1);

namespace Inventory\Alias\Application;

final class AliasId
{

    public function __construct(private int $supplierId, private ProductId $productId)
    {
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }

}
