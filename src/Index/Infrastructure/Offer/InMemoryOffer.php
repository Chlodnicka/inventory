<?php

declare(strict_types=1);

namespace Inventory\Index\Infrastructure\Offer;

use Inventory\Index\Domain\Index;
use Inventory\Index\Domain\IndexCollection;
use Inventory\Index\Domain\Offer;
use Inventory\Index\Domain\OfferFactory;

final class InMemoryOffer implements Offer
{
    private array $memory;

    public function __construct(array $memory)
    {
        $this->memory = $memory;
    }

    public function get(int $supplierId): IndexCollection
    {
        $offerFactory = new OfferFactory();
        return $offerFactory->create($supplierId, $this->memory);
    }

    public function update(Index $nextIndex): void
    {
        $this->memory[$nextIndex->getId()->get()] = $nextIndex->serialize();
        $this->memory[$nextIndex->getId()->get()]['updated_at'] = new \DateTimeImmutable();
    }

    public function create(Index $nextIndex): void
    {
        $this->memory[$nextIndex->getId()->get()] = $nextIndex->serialize();
        $this->memory[$nextIndex->getId()->get()]['created_at'] = new \DateTimeImmutable();
    }

    public function delete(array $indexIds): void
    {
        foreach ($indexIds as $indexId) {
            if (isset($this->memory[(string)$indexId])) {
                $this->memory[$indexId]['quantity'] = 0;
                $this->memory[$indexId]['price'] = 0;
                $this->memory[$indexId]['currency'] = 'PLN';
                $this->memory[$indexId]['deleted_at'] = new \DateTimeImmutable();
            }
        }
    }

    public function find(string $indexId): array
    {
        return $this->memory[$indexId] ?? [];
    }
}
