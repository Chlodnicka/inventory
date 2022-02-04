<?php

declare(strict_types=1);

namespace Inventory\Index\Domain;

final class IndexId
{
    private string $value;

    public function __construct(string $value)
    {
        if ($value === '' || mb_strlen($value) > 45) {
            throw new \Exception("Invalid id value $value");
        }

        $this->value = $value;
    }

    public function get(): string
    {
        return $this->value;
    }
}
