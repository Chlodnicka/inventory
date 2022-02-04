<?php

declare(strict_types=1);

namespace Inventory\Alias\Domain;

use Inventory\Alias\Application\AliasId;
use Inventory\Index\Domain\Quantity;
use Money\Money;

final class Alias
{
    private AliasId $id;
    private Money $initialPrice;
    private Money $purchasePrice;
    private Money $salesPrice;
    private Quantity $quantity;
    private int $changes = 0;

    public function restock(Quantity $quantity): void
    {
        if ($this->quantity->equals($quantity)) {
            return;
        }
        $this->quantity = clone $quantity;
        $this->changes++;
    }

    public function makeReservation(Quantity $quantity): void
    {
        $this->quantity = $this->quantity->subtract($quantity);
        $this->changes++;
    }

    public function cancelReservation(Quantity $quantity): void
    {
        $this->quantity = $this->quantity->add($quantity);
        $this->changes++;
    }

    public function recalculatePrice(
        Money $newPrice,
        PurchasePricePolicy $purchasePricePolicy,
        SalesPricePolicy $salesPricePolicy
    ): void {
        if (!$this->initialPrice->equals($newPrice)) {
            $this->changes++;
            $this->initialPrice = clone $newPrice;
        }

        $newPurchasePrice = $purchasePricePolicy->calculate($this->initialPrice);
        if (!$this->purchasePrice->equals($newPurchasePrice)) {
            $this->purchasePrice = $newPurchasePrice;
            $this->changes++;
        }

        $newSalesPrice = $salesPricePolicy->calculate($this->purchasePrice);
        if (!$this->salesPrice->equals($newSalesPrice)) {
            $this->salesPrice = $newSalesPrice;
            $this->changes++;
        }
    }

    public function getInitialPrice(): Money
    {
        return $this->initialPrice;
    }

    public function hasChanges(): bool
    {
        return $this->changes > 0;
    }


}
