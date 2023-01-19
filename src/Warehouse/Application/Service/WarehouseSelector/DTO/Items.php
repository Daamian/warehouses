<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\WarehouseSelector\DTO;

class Items implements \Iterator
{
    /** @var Item[] */
    private array $items;
    private int $position = 0;

    public function __construct(Item ...$items)
    {
        $this->items = $items;
    }

    public function current(): Item
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}