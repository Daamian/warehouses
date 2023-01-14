<?php

namespace src\Order\Domain;

class Items
{
    /**
     * @var Item[]
     */
    private array $items = [];

    private function __construct(Item ...$items)
    {
        $this->items = $items;
    }

    public static function createEmpty(): self
    {
        return new Items(...[]);
    }

    public function add(Item $item): void
    {
        $this->items[] = $item;
    }

    /** @return Item[] */
    public function toArray(): array
    {
        return $this->items;
    }
}