<?php

namespace Daamian\WarehouseAlgorithm\Warehouse\Application\Service\StateChecker;

interface StateCheckerInterface
{
    public function isAvailable(string $resourceId, int $quantity): bool;
}