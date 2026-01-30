<?php

namespace App\Domains\Loyalty\Persistence\Entities;

use App\Domains\Accounts\Persistence\Entities\User;
use app\Domains\Loyalty\Enums\AchievementType;
use Database\Factories\AchievementFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    /** @use HasFactory<AchievementFactory> */
    use HasFactory;

    /**
     * @return AchievementFactory
     */
    protected static function newFactory(): AchievementFactory
    {
        return AchievementFactory::new();
    }

    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type',
        'threshold',
        'reward',
        'description',
    ];

    /**
     * Attributes cast types
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AchievementType::class,
            'threshold' => 'int',
            'reward' => 'decimal:2',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements');
    }
}
