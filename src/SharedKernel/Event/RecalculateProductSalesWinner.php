<?php

declare(strict_types=1);

namespace Inventory\SharedKernel\Event;

use Inventory\Alias\Application\ProductId;
use Inventory\SharedKernel\Event;

final class RecalculateProductSalesWinner implements Event
{
    public function __construct(private ProductId $productId)
    {
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
