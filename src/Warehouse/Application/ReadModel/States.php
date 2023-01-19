<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\ReadModel;

class States implements \Iterator
{
    /** @var WarehouseState[] */
    private array $items;
    private int $position = 0;

    public function __construct(WarehouseState ...$items)
    {
        $this->items = $items;
    }

    public function current(): WarehouseState
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