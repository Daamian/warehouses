<?php

namespace Daamian\WarehouseAlgorithm\Order\Application\Command\CreateOrder;

final readonly class CreateOrderCommand
{
    private string $id;
    private string $userId;
    private array $items;

    public function __construct(string $id, string $userId, Item ...$items)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->items = $items;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}