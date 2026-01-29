<?php

namespace App\Domains\Store\Persistence\Entities;

use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'amount',
        'purchased_at',
        'description',
    ];

    /**
     * Attributes cast types
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'purchased_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
