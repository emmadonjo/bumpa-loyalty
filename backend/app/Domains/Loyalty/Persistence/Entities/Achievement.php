<?php

namespace App\Domains\Loyalty\Persistence\Entities;

use app\Domains\Loyalty\Enums\AchievementType;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
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
}
