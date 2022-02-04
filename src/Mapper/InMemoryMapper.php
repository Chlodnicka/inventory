<?php

declare(strict_types=1);

namespace Inventory\Mapper;

final class InMemoryMapper implements Mapper
{
    public function map(int $supplierId, array $filenames): array
    {
        return [
            [
                'id'       => '123',
                'price'    => 35.44,
                'currency' => 'EUR',
                'quantity' => 40
            ],
            [
                'id'       => '234',
                'price'    => 1135.48,
                'currency' => 'PLN',
                'quantity' => 23
            ],
            [
                'id'       => '345',
                'price'    => 453.20,
                'quantity' => 0
            ],
            [
                'id'       => '456',
                'price'    => 355.54,
                'currency' => 'PLN',
                'quantity' => 273
            ],
            [
                'id'       => '567',
                'price'    => 11.00,
                'quantity' => 2
            ],
            [
                'id'       => '678',
                'price'    => 456.00,
                'quantity' => 66
            ]
        ];
    }
}
