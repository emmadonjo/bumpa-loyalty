<?php

namespace App\Infrastructure\Messaging\Producers;

use App\Infrastructure\Messaging\Contracts\MessageProducerInterface;

class MemoryMessageProducer implements MessageProducerInterface
{
    public function publish(string $stream, array $payload): void
    {
        event(new $stream($payload));
    }
}
