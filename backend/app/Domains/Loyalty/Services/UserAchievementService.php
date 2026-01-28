<?php

namespace App\Domains\Loyalty\Services;

use App\Domains\Loyalty\Persistence\Contracts\UserAchievementRepositoryInterface;
use App\Domains\Loyalty\Persistence\Entities\UserAchievement;
use Illuminate\Contracts\Pagination\Paginator;

class UserAchievementService
{
    public function __construct(
        private readonly UserAchievementRepositoryInterface $repository,
    ){}

    /**
     * @param array $params
     * @return Paginator<UserAchievement>
     */
    public function get(array $params): Paginator
    {
        return $this->repository->get($params);
    }
}
