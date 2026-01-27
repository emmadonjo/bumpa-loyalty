<?php

declare(strict_types=1);

namespace App\Domains\Accounts\Enums;

use App\Domains\Shared\Concerns\HasEnum;

enum UserRole: string
{
    use HasEnum;
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
}
