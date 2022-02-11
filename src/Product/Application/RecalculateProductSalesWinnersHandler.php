<?php

declare(strict_types=1);

namespace Inventory\Product\Application;

use Inventory\Product\Domain\ProductSales;
use Inventory\Product\Domain\ProductSalesPolicy;
use Inventory\SharedKernel\Event\RecalculateProductSalesWinner;

final class RecalculateProductSalesWinnersHandler
{
    //todo fix this stupid name -> this recalculates product price and winning supplier

    private array $productSalesPolicies;

    public function __construct(private ProductSales $productSales, ProductSalesPolicy ...$productSalesPolicies)
    {
        $this->productSalesPolicies = $productSalesPolicies;
    }

    public function __invoke(RecalculateProductSalesWinner $command): void
    {
        foreach ($this->productSalesPolicies as $policy) {
            if ($policy->supports($command->getProductId())) {
                $productSalesWinner = $policy->calculate($command->getProductId());
                $this->productSales->save($command->getProductId(), $productSalesWinner, $policy->getType());
            }
        }
    }
}
