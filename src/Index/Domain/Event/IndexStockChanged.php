<?php

declare(strict_types=1);

namespace Inventory\Index\Domain\Event;

use Inventory\Index\Domain\IndexId;
use Inventory\Index\Domain\Quantity;
use Money\Money;

final class IndexStockChanged extends IndexChanged
{
    private Money $price;
    private bool $priceChanged;
    private Quantity $quantity;
    private bool $quantityChanged;

    public function __construct(
        int $supplierId,
        IndexId $indexId,
        Money $price,
        bool $priceChanged,
        Quantity $quantity,
        bool $quantityChanged
    ) {
        parent::__construct($supplierId, $indexId);
        $this->price = $price;
        $this->priceChanged = $priceChanged;
        $this->quantity = $quantity;
        $this->quantityChanged = $quantityChanged;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function isPriceChanged(): bool
    {
        return $this->priceChanged;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function isQuantityChanged(): bool
    {
        return $this->quantityChanged;
    }
}
