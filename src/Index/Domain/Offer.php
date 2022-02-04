<?php

namespace Inventory\Index\Domain;

interface Offer
{
    public function get(int $supplierId): IndexCollection;

    public function update(Index $nextIndex): void;

    public function create(Index $nextIndex): void;

    public function delete(array $indexIds): void;

}
