<?php

namespace App\Domains\Loyalty\Services;

use App\Domains\Loyalty\Persistence\Contracts\UserAchievementRepositoryInterface;
use App\Domains\Loyalty\Persistence\Entities\UserAchievement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class UserAchievementService
{
    public function __construct(
        private UserAchievementRepositoryInterface $repository,
    ){}

    /**
     * @param array $params
     * @return LengthAwarePaginator<UserAchievement>
     */
    public function get(array $params): LengthAwarePaginator
    {
        return $this->repository->get($params);
    }
}
