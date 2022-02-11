<?php

declare(strict_types=1);

namespace Inventory\Alias\Domain;

use Inventory\Alias\Application\AliasId;
use Inventory\Index\Domain\Index;
use Inventory\Index\Domain\IndexId;

final class AliasFactory
{
    public function __construct(private ?PricePolicy $purchasePricePolicy, private ?PricePolicy $salesPricePolicy)
    {
    }

    public function create(AliasId $aliasId, IndexId $indexId, Index $index): Alias
    {
        $alias = $this->createPureAlias($aliasId, $indexId, $index);
        $alias->create();
        return $alias;
    }

//    public function createFromExisting(array $payload): void
//    {
//        //todo validation ??
//        $aliasId = new AliasId($)
//        return $this->createPureAlias();
//    }

    private function createPureAlias(AliasId $aliasId, IndexId $indexId, Index $index): Alias
    {
        $initialPrice = $index->getPrice();
        $purchasePrice = $this->purchasePricePolicy ? $this->purchasePricePolicy->calculate(
            $initialPrice
        ) : $initialPrice;
        $salesPrice = $this->salesPricePolicy ? $this->salesPricePolicy->calculate($purchasePrice) : $purchasePrice;

        return new Alias(
            $aliasId,
            $indexId,
            $initialPrice,
            $purchasePrice,
            $salesPrice,
            $index->getQuantity()
        );
    }
}
