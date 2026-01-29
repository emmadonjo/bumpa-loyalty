<?php

namespace App\Infrastructure\Messaging\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable;
    use SerializesModels;
    public function __construct(
        public array $payload,
    ){}
}
