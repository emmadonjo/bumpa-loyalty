<?php

namespace App\Infrastructure\Messaging\Contracts;

interface MessageProducerInterface
{
    public function publish(string $stream, array $payload): void;
}
