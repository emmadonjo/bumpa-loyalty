<?php

namespace App\Domains\Accounts\Persistence\Entities;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use App\Domains\Loyalty\Persistence\Entities\Badge;
use App\Domains\Loyalty\Persistence\Entities\LoyaltyTracker;
use App\Domains\Store\Persistence\Entities\Purchase;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * @return UserFactory|Factory
     */
    protected static function newFactory(): UserFactory|Factory
    {
        return UserFactory::new();
    }

    /**
     * @return BelongsToMany<Achievement>
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at');
    }

    /**
     * @return BelongsToMany<Badge>
     */
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('awarded_at');
    }

    public function loyaltyInfo(): HasOne
    {
        return $this->hasOne(LoyaltyTracker::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    /**
     * @return HasMany<{Purchase>}
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
