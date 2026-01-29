<?php

namespace App\Domains\Store\Enums;

use App\Domains\Shared\Concerns\HasEnum;

enum PurchaseStatus: string
{
    use HasEnum;

    case SUCCESSFUL = 'successful';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case ABANDONED = 'abandoned';
    case Cancelled = 'cancelled';
}
