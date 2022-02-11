<?php

declare(strict_types=1);

namespace Inventory\Index\Domain;

use Inventory\Index\Domain\Event\IndexChanged;
use Inventory\Index\Domain\Event\IndexStockChanged;
use Money\Currency;
use Money\Money;

final class Index
{
    private const DEFAULT_CURRENCY = 'PLN';

    private IndexId $id;
    private Money $money;
    private Quantity $quantity;

    public function __construct(IndexId $id, Money $money, Quantity $quantity)
    {
        $this->id = $id;
        $this->money = $money;
        $this->quantity = $quantity;
    }

    public static function create(array $payload): Index
    {
        ///todo validation
        ///
        return new self(
            new IndexId((string)$payload['id']),
            new Money((int)($payload['price'] * 100), new Currency($payload['currency'] ?? self::DEFAULT_CURRENCY)),
            new Quantity($payload['quantity'])
        );
    }

    public function getId(): IndexId
    {
        return $this->id;
    }

    public function getPrice(): Money
    {
        return clone $this->money;
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function getChecksum(): string
    {
        return serialize($this);
    }

    public function hasChanges(Index $nextIndex): bool
    {
        return $this->getChecksum() !== $nextIndex->getChecksum();
    }

    public function getChanges(int $supplierId, Index $nextIndex): IndexChanged
    {
        return new IndexStockChanged(
            $supplierId,
            $this->getId(),
            $nextIndex->getPrice(),
            !$nextIndex->getPrice()->equals($this->money),
            $nextIndex->getQuantity(),
            !$nextIndex->getQuantity()->equals($this->quantity)
        );
    }

    public function serialize(): array
    {
        return [
            'id'       => $this->id->get(),
            'price'    => $this->money->getAmount() / 100,
            'currency' => $this->money->getCurrency()->getCode(),
            'quantity' => $this->quantity->get()
        ];
    }
}
