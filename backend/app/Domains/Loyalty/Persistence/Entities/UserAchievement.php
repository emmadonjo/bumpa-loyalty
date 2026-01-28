<?php

namespace App\Domains\Loyalty\Persistence\Entities;

use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    /**
     * @var bool $timestamp
     */
    protected bool $timestamp = false;

    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'achievement_id',
        'unlocked_at',
    ];

    /**
     * Attributes cast types
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unlocked_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Achievement>
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class, 'achievement_id');
    }
}
