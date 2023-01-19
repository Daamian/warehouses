<?php

namespace Daamian\WarehouseAlgorithm\Order\Domain;

use Daamian\WarehouseAlgorithm\Order\Domain\Event\OrderInitialized;
use Daamian\WarehouseAlgorithm\Shared\AggregateRoot;

class Order extends AggregateRoot
{
    private string $id;
    private Items $items;
    private string $userId;
    private Status $status;

    private function __construct(string $id, Items $items, string $userId)
    {
        $this->id = $id;
        $this->items = $items;
        $this->userId = $userId;
        $this->status = Status::INITIAL;
        $this->registerEvent(new OrderInitialized($this->id, $items, $userId));
    }

    public static function createInitial(string $id, Items $items, string $user): self
    {
        return new Order($id, $items, $user);
    }

    public function confirm(): void
    {
        $this->status = Status::CONFIRMED;
    }

    public function reject(): void
    {
        $this->status = Status::REJECTED;
    }
}