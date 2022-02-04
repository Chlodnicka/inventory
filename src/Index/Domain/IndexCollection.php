<?php

declare(strict_types=1);

namespace Inventory\Index\Domain;

use Inventory\Index\Domain\Event\IndexChanged;
use JetBrains\PhpStorm\Pure;

final class IndexCollection
{
    private int $supplierId;
    /** @var Index[] */
    private array $valid;
    private array $invalid;

    public function __construct(int $supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function add(Index $index): void
    {
        $serializedId = $index->getId()->get();
        $this->valid[$serializedId] = $index;
    }

    public function failed(array $payload, \Exception $exception): void
    {
        $this->invalid[] = [
            'exception' => $exception,
            'payload'   => $payload
        ];
    }

    public function count(): int
    {
        return \count($this->valid);
    }

    public function get(): array
    {
        return $this->valid;
    }

    public function exists(string $serializedId): bool
    {
        return isset($this->valid[$serializedId]);
    }

    public function hasChanges(Index $nextIndex): bool
    {
        return $this->valid[$nextIndex->getId()->get()]->hasChanges($nextIndex);
    }

    public function getKeys(): array
    {
        return \array_keys($this->valid);
    }

    #[Pure] public function diff(IndexCollection $nextCollection): array
    {
        return \array_diff($this->getKeys(), $nextCollection->getKeys());
    }

    public function getChanges(Index $nextIndex): IndexChanged
    {
        return $this->valid[$nextIndex->getId()->get()]->getChanges($this->supplierId, $nextIndex);
    }
}
