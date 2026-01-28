<?php

namespace App\Domains\Accounts\Persistence\Repositories;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Accounts\Persistence\Contracts\UserRepositoryInterface;
use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param UserRole $role
     * @param array $params
     * @param bool $includedLoyaltyInfo
     * @return LengthAwarePaginator<User>
     */
    public function getUsersByRole(
        UserRole $role,
        array $params = [],
        bool $includedLoyaltyInfo = false,
    ): LengthAwarePaginator {
        $perPage = $params['per_page'] ?? 20;
        $search = $params['search'] ?? null;
        return User::when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $searchTerm = "%$search%";
                return $query->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        })
            ->withCount('achievements')
            ->when($includedLoyaltyInfo, function ($query) {
                $query->with(['loyaltyInfo', 'loyaltyInfo.currentBadge']);
            })
            ->where('role', $role->value)
            ->paginate($perPage);
    }

    /**
     * Retrieve a user alongside their loyalty info.
     * @param int $id
     * @return User|null
     */
    public function getUserWithLoyaltyInfo(int $id): ?User
    {
       return User::where('id', $id)
           ->with([
               'loyaltyInfo',
               'loyaltyInfo.currentBadge',
               'achievements',
               'badges'
           ])
           ->first();
    }

    /**
     * Find a user by their email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
