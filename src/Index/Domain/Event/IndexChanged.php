<?php

declare(strict_types=1);

namespace Inventory\Index\Domain\Event;

use Inventory\Index\Domain\IndexId;
use Inventory\SharedKernel\Event;

abstract class IndexChanged implements Event
{
    public function __construct(protected int $supplierId, protected IndexId $indexId)
    {
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    public function getIndexId(): IndexId
    {
        return $this->indexId;
    }
}
