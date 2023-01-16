<?php

namespace Daamian\WarehouseAlgorithm\Shared;

interface CommandBusInterface
{
    public function dispatch($command): void;
}