<?php

namespace src\Order\Domain;

use src\Order\Domain\Event\OrderInitialized;
use src\Shared\AggregateRoot;

class Order extends AggregateRoot
{
    private string $id;
    private Items $items;
    private string $userId;
    private Status $status;

    private function __construct(string $id, Items $items, string $userId, Status $status)
    {
        $this->id = $id;
        $this->items = $items;
        $this->userId = $userId;
        $this->status = $status;
        $this->registerEvent(new OrderInitialized($this->id, $items, $userId));
    }

    public static function createInitial(string $id, Items $items, string $user): self
    {
        return new Order($id, $items, $user, Status::INITIAL);
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