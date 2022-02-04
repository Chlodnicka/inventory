<?php

namespace Inventory\Tests\Index\Application;

use Inventory\Index\Application\IndexImportService;
use Inventory\Index\Domain\Event\IndexCreated;
use Inventory\Index\Domain\Event\IndexStockChanged;
use Inventory\Index\Domain\OfferFactory;
use Inventory\Index\Infrastructure\Offer\InMemoryOffer;
use Inventory\Mapper\InMemoryMapper;
use Inventory\SharedKernel\EventPublisher;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Inventory\Index\Application\IndexImportService
 */
class IndexImportServiceTest extends TestCase
{
    public function testShouldDoSth(): void
    {
        // Given
        $mapper = new InMemoryMapper();
        $offer = new InMemoryOffer(self::CURRENT_OFFER);
        $offerFactory = new OfferFactory();
        $eventPublisher = new EventPublisher();
        $indexImportService = new IndexImportService($mapper, $offerFactory, $offer, $eventPublisher);

        // When
        $indexImportService->import(1, ['filename1', 'filename2']);

        // Then
        self::assertSame(1, $eventPublisher->getNumberOfOccurances(IndexCreated::class));
        self::assertSame(5, $eventPublisher->getNumberOfOccurances(IndexStockChanged::class));

        self::assertFalse(isset($offer->find('123')['updated_at']));
        self::assertFalse(isset($offer->find('123')['created_at']));
        self::assertFalse(isset($offer->find('123')['deleted_at']));

        self::assertFalse(isset($offer->find('234')['updated_at']));
        self::assertTrue(isset($offer->find('234')['created_at']));
        self::assertFalse(isset($offer->find('234')['deleted_at']));

        self::assertTrue(isset($offer->find('345')['updated_at']));
        self::assertFalse(isset($offer->find('345')['created_at']));
        self::assertFalse(isset($offer->find('345')['deleted_at']));

        self::assertTrue(isset($offer->find('456')['updated_at']));
        self::assertFalse(isset($offer->find('456')['created_at']));
        self::assertFalse(isset($offer->find('456')['deleted_at']));

        self::assertTrue(isset($offer->find('567')['updated_at']));
        self::assertFalse(isset($offer->find('567')['created_at']));
        self::assertFalse(isset($offer->find('567')['deleted_at']));

        self::assertTrue(isset($offer->find('678')['updated_at']));
        self::assertFalse(isset($offer->find('678')['created_at']));
        self::assertFalse(isset($offer->find('678')['deleted_at']));

        self::assertFalse(isset($offer->find('789')['updated_at']));
        self::assertFalse(isset($offer->find('789')['created_at']));
        self::assertTrue(isset($offer->find('789')['deleted_at']));
    }

    private const CURRENT_OFFER = [
        123 => [
            'id'       => '123',
            'price'    => 35.44,
            'currency' => 'EUR',
            'quantity' => 40
        ],
        345 => [
            'id'       => '345',
            'price'    => 453.20,
            'quantity' => 5
        ],
        456 => [
            'id'       => '456',
            'price'    => 399.54,
            'currency' => 'PLN',
            'quantity' => 273
        ],
        567 => [
            'id'       => '567',
            'price'    => 11.00,
            'currency' => 'EUR',
            'quantity' => 2
        ],
        678 => [
            'id'       => '678',
            'price'    => 751.00,
            'quantity' => 36
        ],
        789 => [
            'id'       => '789',
            'price'    => 766.00,
            'quantity' => 46
        ]
    ];
}
