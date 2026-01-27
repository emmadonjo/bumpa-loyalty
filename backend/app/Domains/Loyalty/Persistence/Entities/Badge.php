<?php

namespace App\Domains\Loyalty\Persistence\Entities;

use Illuminate\Database\Eloquent\Model;

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

    public function getIconUrlAttribute(): ?string
    {
        $icon = $this->getAttribute('icon');

        return empty($icon)
            ? null
            : config('app.url') . "/{$icon}";
    }
}
