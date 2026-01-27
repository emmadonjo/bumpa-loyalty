<?php

namespace app\Domains\Loyalty\Enums;

use App\Domains\Shared\Concerns\HasEnum;


enum AchievementType: string
{
    use HasEnum;
    case PURCHASE_COUNT = 'purchase_count';
    case TOTAL_SPEND = 'total_spend';
}
