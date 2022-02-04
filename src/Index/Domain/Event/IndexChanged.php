<?php

declare(strict_types=1);

namespace Inventory\Index\Domain\Event;

use Inventory\Index\Domain\IndexId;
use Inventory\SharedKernel\Event;

abstract class IndexChanged implements Event
{
    protected int $supplierId;
    protected IndexId $indexId;

    public function __construct(int $supplierId, IndexId $indexId)
    {
        $this->supplierId = $supplierId;
        $this->indexId = $indexId;
    }

    public function getSupplierId(): int
    {
        return $this->supplierId;
    }
}
