<?php

declare(strict_types=1);

namespace Inventory\Index\Domain;

use Psr\Log\LoggerInterface;

final class OfferFactory
{
    public function create(int $supplierId, array $payload): IndexCollection
    {
        $indexes = new IndexCollection($supplierId);
        foreach ($payload as $item) {
            try {
                $index = Index::create($item);
                $indexes->add($index);
            } catch (\Exception $e) {
                $indexes->failed($item, $e);
            }
        }
        return $indexes;
    }
}
