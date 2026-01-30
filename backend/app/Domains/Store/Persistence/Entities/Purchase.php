<?php

namespace App\Domains\Store\Persistence\Entities;

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Store\Enums\PurchaseStatus;
use Database\Factories\PurchaseFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    /** @use HasFactory<PurchaseFactory> */
    use HasFactory;

    /**
     * Mass-assignable attributes
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'amount',
        'purchased_at',
        'description',
        'reference',
        'external_reference',
        'provider',
        'payment_method',
        'fees',
        'currency',
        'request_payload',
        'response_payload',
        'status',
    ];

    /**
     * @return PurchaseFactory|Factory
     */
    protected static function newFactory(): PurchaseFactory|Factory
    {
        return PurchaseFactory::new();
    }

    /**
     * Attributes cast types
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'purchased_at' => 'datetime',
            'status' => PurchaseStatus::class,
            'fees' => 'decimal:2',
            'request_payload' => 'json',
            'response_payload' => 'json',
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
