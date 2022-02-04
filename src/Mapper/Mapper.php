<?php

namespace Inventory\Mapper;

interface Mapper
{
    public function map(int $supplierId, array $filenames): array;
}
