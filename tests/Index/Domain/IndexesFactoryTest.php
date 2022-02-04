<?php

namespace Inventory\Tests\Index\Domain;

use Inventory\Index\Application\IndexImportService;
use Inventory\Index\Domain\OfferFactory;
use Inventory\Mapper\InMemoryMapper;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Inventory\Index\Domain\OfferFactory
 */
class IndexesFactoryTest extends TestCase
{
    public function testShouldReadFileAndCreateIndexes(): void
    {
        // Given
        $supplierId = 1;
        $payload = (new InMemoryMapper())->map($supplierId, ['filename1.xml', 'filename2.csv']);
        $indexesFactory = new OfferFactory();

        // When
        $result = $indexesFactory->create($supplierId, $payload);

        // Then
        self::assertSame(5, $result->count());
    }
}
