<?php

namespace App\Domains\Accounts\Services;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Accounts\Persistence\Contracts\UserRepositoryInterface;
use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository,
    ){}

    /**
     * @param array $params
     * @return LengthAwarePaginator<User>
     */
    public function getCustomers(array $params = []): LengthAwarePaginator
    {
        return $this->repository->getUsersByRole(UserRole::CUSTOMER, $params, true);
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function getCustomerWithLoyaltyInfo(int $userId): ?User
    {
        return $this->repository->getUserWithLoyaltyInfo($userId);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->repository->findByEmail($email);
    }
}
