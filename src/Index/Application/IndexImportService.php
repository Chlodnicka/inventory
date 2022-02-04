<?php

declare(strict_types=1);

namespace Inventory\Index\Application;

use Inventory\Index\Domain\Event\IndexCreated;
use Inventory\Index\Domain\Event\IndexStockChanged;
use Inventory\Index\Domain\IndexCollection;
use Inventory\Index\Domain\IndexId;
use Inventory\Index\Domain\Offer;
use Inventory\Index\Domain\OfferFactory;
use Inventory\Index\Domain\Quantity;
use Inventory\Mapper\Mapper;
use Inventory\SharedKernel\EventPublisher;
use Money\Currency;
use Money\Money;

final class IndexImportService
{
    private Mapper $mapper;
    private OfferFactory $offerFactory;
    private Offer $offer;
    private EventPublisher $eventPublisher;

    public function __construct(
        Mapper $mapper,
        OfferFactory $offerFactory,
        Offer $offer,
        EventPublisher $eventPublisher
    ) {
        $this->mapper = $mapper;
        $this->offerFactory = $offerFactory;
        $this->offer = $offer;
        $this->eventPublisher = $eventPublisher;
    }

    public function import(int $supplierId, array $filenames): void
    {
        $payload = $this->mapper->map($supplierId, $filenames);
        $next = $this->offerFactory->create($supplierId, $payload);
        $current = $this->offer->get($supplierId);

        $failed = [];
        foreach ($next->get() as $serializedId => $nextIndex) {
            try {
                if ($current->exists((string)$serializedId)) {
                    if ($current->hasChanges($nextIndex)) {
                        $this->offer->update($nextIndex);
                        $this->eventPublisher->publish($current->getChanges($nextIndex));
                    }
                } else {
                    $this->offer->create($nextIndex);
                    $this->eventPublisher->publish(new IndexCreated($supplierId, $nextIndex));
                }
            } catch (\Exception $e) {
                //todo log
                $failed[] = $nextIndex->getId()->get();
            }
        }

        $toDelete = \array_merge($failed, $current->diff($next));
        $this->offer->delete($toDelete);
        foreach ($toDelete as $toDeleteIndex) {
            $this->eventPublisher->publish(
                new IndexStockChanged(
                    $supplierId,
                    new IndexId((string)$toDeleteIndex),
                    new Money(0, new Currency('PLN')),
                    true,
                    new Quantity(0),
                    true
                )
            );
        }
    }
}
