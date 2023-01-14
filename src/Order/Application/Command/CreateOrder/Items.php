<?php

namespace src\Order\Application\Command\CreateOrder;

class Items
{
    /** @var Item[] */
    private array $items = [];

    public function __construct(Item ...$items)
    {
        $this->items = $items;
    }
}