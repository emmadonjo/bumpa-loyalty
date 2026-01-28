<?php

namespace App\Domains\Accounts\Services;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Accounts\Persistence\Contracts\UserRepositoryInterface;
use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Contracts\Pagination\Paginator;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ){}

    /**
     * @param array $params
     * @return Paginator<User>
     */
    public function getCustomers(array $params = []): Paginator
    {
        return $this->repository->getUsersByRole(UserRole::CUSTOMER, $params);
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
