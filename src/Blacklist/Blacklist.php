<?php

declare(strict_types=1);

namespace Inventory\Blacklist;

use Inventory\Alias\Domain\Alias;

final class Blacklist
{
    public function filter(Alias $alias): bool
    {
        //blacklist has changed alias status?
        return true;
    }
}
