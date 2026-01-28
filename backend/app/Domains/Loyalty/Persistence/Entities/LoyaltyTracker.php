<?php

namespace App\Domains\Accounts\Persistence\Entities;

use app\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Loyalty\Persistence\Entities\Badge;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTracker extends Model
{
    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'purchase_count',
        'total_spent',
        'payout_balance',
        'current_badge_id',
        'total_achievements',
    ];

    protected function casts(): array
    {
        return [
            'purchase_count' => 'int',
            'total_spent' => 'decimal',
            'payout_balance' => 'decimal',
            'total_achievements' => 'int',
        ];
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentBadge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'current_badge_id');
    }
}
