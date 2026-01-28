<?php

namespace App\Domains\Loyalty\Persistence\Contracts;

use App\Domains\Loyalty\Persistence\Entities\UserAchievement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserAchievementRepositoryInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator<UserAchievement>
     */
    public function get(array $params = []): LengthAwarePaginator;
}
