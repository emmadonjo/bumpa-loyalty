<?php

namespace App\Domains\Accounts\Persistence\Contracts;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
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
    ): LengthAwarePaginator;

    /**
     * Get a user's summarised loyalty info
     * @param int $id
     * @return User|null
     */
    public function getUserWithLoyaltyInfo(int $id): ?User;

    /**
     * Find a user by their email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user their id
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;
}
