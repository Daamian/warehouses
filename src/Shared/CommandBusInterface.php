<?php

namespace src\Shared;

interface CommandBusInterface
{
    public function dispatch($command): void;
}