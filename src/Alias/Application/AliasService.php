<?php

declare(strict_types=1);

namespace Inventory\Alias\Application;

use Inventory\Alias\Domain\Alias;
use Inventory\Alias\Domain\PurchasePricePolicy;
use Inventory\Alias\Domain\SalesPricePolicy;
use Inventory\Blacklist\Blacklist;
use Inventory\Index\Domain\Event\IndexStockChanged;
use Inventory\Index\Domain\Index;
use Inventory\Index\Domain\IndexId;
use Inventory\Index\Domain\Quantity;
use Inventory\SharedKernel\EventPublisher;

final class AliasService
{
    private Aliases $aliases;
    private EventPublisher $eventPublisher;
    private Blacklist $blacklist;

    public function __construct(Aliases $aliases, EventPublisher $eventPublisher, Blacklist $blacklist)
    {
        $this->aliases = $aliases;
        $this->eventPublisher = $eventPublisher;
        $this->blacklist = $blacklist;
    }

    public function create(ProductId $productId, IndexId $indexId, Index $index): void
    {
        $aliasId = new AliasId($productId, $indexId);
        $alias = new Alias($aliasId, $index);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function updateStock(IndexStockChanged $indexStockChanged): void
    {
        $alias = $this->aliases->get(new AliasId());
        if ($indexStockChanged->isQuantityChanged()) {
            $alias->restock($indexStockChanged->getQuantity());
        }
        if ($indexStockChanged->getPrice()) {
            $alias->recalculatePrice($indexStockChanged->getPrice(), new PurchasePricePolicy(), new SalesPricePolicy());
        }
        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function productChanged(): void
    {
        $alias = $this->aliases->get(new AliasId());
        $alias->recalculatePrice($alias->getInitialPrice(), new PurchasePricePolicy(), new SalesPricePolicy());
        $this->aliases->save($alias);
    }

    public function makeReservation(Quantity $quantity): void
    {
        $alias = $this->aliases->get(new AliasId());
        $alias->makeReservation($quantity);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    public function cancelReservation(Quantity $quantity): void
    {
        $alias = $this->aliases->get(new AliasId());
        $alias->cancelReservation($quantity);
        $this->aliases->save($alias);
        $this->export($alias);
    }

    private function export(Alias $alias): void
    {
        if ($alias->hasChanges() || $this->blacklist->filter($alias)) {
            $this->eventPublisher->publish(new RecalculateBuyBox($alias->getProductId()));
        }
    }
}
