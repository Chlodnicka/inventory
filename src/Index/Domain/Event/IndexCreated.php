<?php

declare(strict_types=1);

namespace Inventory\Index\Domain\Event;

use Inventory\Index\Domain\Index;

final class IndexCreated extends IndexChanged
{
    private Index $index;

    public function __construct(int $supplierId, Index $index)
    {
        parent::__construct($supplierId, $index->getId());
        $this->index = $index;
    }
}
