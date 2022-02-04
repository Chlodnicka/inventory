<?php

namespace Inventory\Tests;

use Inventory\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testShouldReturnTrue(): void
    {
        // Given
        $service = new Service();

        // When
        $result = $service->index();

        // Then
        self::assertTrue($result);
    }
}
