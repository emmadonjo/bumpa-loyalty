<?php

namespace App\Domains\Loyalty\Persistence\Entities;

use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'name',
        'icon',
        'achievements_required',
    ];

    /**
     * Attributes casts types
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'achievements_required' => 'int',
        ];
    }

    /**
     * @var string[]
     */
    protected $appends = [
        'icon_url',
    ];

    /**
     * @return string|null
     */
    public function getIconUrlAttribute(): ?string
    {
        $icon = $this->getAttribute('icon');

        return empty($icon)
            ? null
            : config('app.url') . "/{$icon}";
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
