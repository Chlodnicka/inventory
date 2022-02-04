<?php

declare(strict_types=1);

namespace Inventory\Alias\Application;

use Inventory\Alias\Domain\Alias;

interface Aliases
{
    public function get(AliasId $aliasId): Alias;

    public function save(Alias $alias): void;
}
