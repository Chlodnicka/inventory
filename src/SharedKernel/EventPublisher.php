<?php

declare(strict_types=1);

namespace Inventory\SharedKernel;

final class EventPublisher
{
    /** @var Event[] */
    private array $memory = [];
    /** @var int[] */
    private array $numberOfOccurances = [];

    public function publish(Event $event): void
    {
        $this->memory[] = $event;
        if (!isset($this->numberOfOccurances[get_class($event)])) {
            $this->numberOfOccurances[get_class($event)] = 0;
        }
        $this->numberOfOccurances[get_class($event)]++;
    }

    public function get(): array
    {
        return $this->memory;
    }

    public function getNumberOfOccurances(string $eventType): int
    {
        return isset($this->numberOfOccurances[$eventType]) ? (int)$this->numberOfOccurances[$eventType] : 0;
    }


}
