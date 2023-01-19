<?php

namespace Daamian\WarehouseAlgorithm\Order\Domain\Event;

use Daamian\WarehouseAlgorithm\Order\Domain\Items;
use Daamian\WarehouseAlgorithm\Shared\EventInterface;

class OrderInitialized implements EventInterface
{
    private string $id;
    private Items $items;
    private string $userId;

    public function __construct(string $id, Items $items, string $userId)
    {
        $this->id = $id;
        $this->items = $items;
        $this->userId = $userId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getItems(): Items
    {
        return $this->items;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}