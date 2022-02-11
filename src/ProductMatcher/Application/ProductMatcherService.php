<?php

declare(strict_types=1);

namespace Inventory\ProductMatcher\Application;

use Inventory\Index\Domain\Event\IndexCreated;

final class ProductMatcherService
{
    public function link(IndexCreated $created): void
    {
        //link product and index if possible -> create alias (--> recalculate product sales winner - this is alias responsibility)
        //else: create candidates to link list if possible and save
    }

    public function linkOnChange(IndexContentChanged $contentChanged): void {
        //link product and index if possible -> create alias (--> recalculate product sales winner - this is alias responsibility)
        //else: create candidates to link list if possible and save
        //if has been already linked and is no more or linked product changed
        //  -> detach previous product and remove alias (--> recalculate product sales winner - this is alias responsibility)
    }
}
