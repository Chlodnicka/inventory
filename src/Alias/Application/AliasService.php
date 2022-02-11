<?php

declare(strict_types=1);

namespace Inventory\Alias\Application;

use Inventory\Alias\Domain\Alias;
use Inventory\Alias\Domain\AliasFactory;
use Inventory\Alias\Domain\Products;
use Inventory\Alias\Domain\PurchasePricePolicy;
use Inventory\Alias\Domain\SalesPricePolicy;
use Inventory\Alias\Domain\Suppliers;
use Inventory\Blacklist\Blacklist;
use Inventory\Index\Domain\Event\IndexStockChanged;
use Inventory\Index\Domain\Index;
use Inventory\Index\Domain\IndexId;
use Inventory\Index\Domain\Quantity;
use Inventory\SharedKernel\Event\RecalculateProductSalesWinner;
use Inventory\SharedKernel\EventPublisher;

final class AliasService
{
    public function __construct(
        private AliasFactory $aliasFactory,
        private Aliases $aliases,
        private Products $product,
        private Suppliers $suppliers,
        private EventPublisher $eventPublisher,
        private Blacklist $blacklist
    ) {
    }

    public function create(int $supplierId, ProductId $productId, IndexId $indexId, Index $index): void
    {
        $aliasId = new AliasId($supplierId, $productId);
        $alias = $this->aliasFactory->create($aliasId, $indexId, $index);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function updateStock(IndexStockChanged $indexStockChanged): void
    {
        $productId = $this->product->getProductIdByIndex(
            $indexStockChanged->getSupplierId(),
            $indexStockChanged->getIndexId()
        );

        $alias = $this->aliases->get(new AliasId($indexStockChanged->getSupplierId(), $productId));

        if ($indexStockChanged->isQuantityChanged()) {
            $alias->restock($indexStockChanged->getQuantity());
        }

        if ($indexStockChanged->getPrice()) {
            $alias->recalculatePrice($indexStockChanged->getPrice(), new PurchasePricePolicy(), new SalesPricePolicy());
        }

        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function productChanged(ProductId $productId): void
    {
        $suppliers = $this->suppliers->getByProductId($productId);
        $anyAliasHasChanged = false;

        foreach ($suppliers as $supplierId) {
            $alias = $this->aliases->get(new AliasId($supplierId, $productId));
            $alias->recalculatePrice($alias->getInitialPrice(), new PurchasePricePolicy(), new SalesPricePolicy());
            $this->aliases->save($alias);
            $anyAliasHasChanged = $alias->hasChanges() ? true : $anyAliasHasChanged;
        }

        if ($anyAliasHasChanged) {
            $this->eventPublisher->publish(new RecalculateProductSalesWinner($productId));
        }
    }

    public function makeReservation(AliasId $aliasId, Quantity $quantity): void
    {
        $alias = $this->aliases->get($aliasId);
        $alias->makeReservation($quantity);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function cancelReservation(AliasId $aliasId, Quantity $quantity): void
    {
        $alias = $this->aliases->get($aliasId);
        $alias->cancelReservation($quantity);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    private function export(Alias $alias): void
    {
        if ($alias->hasChanges() || $this->blacklist->filter($alias)) {
            $this->eventPublisher->publish(new RecalculateProductSalesWinner($alias->getProductId()));
        }
    }
}
