<?php

namespace App\Domains\Loyalty\Persistence\Contracts;

use App\Domains\Loyalty\Persistence\Entities\UserAchievement;
use Illuminate\Contracts\Pagination\Paginator;

interface UserAchievementRepositoryInterface
{
    /**
     * @param array $params
     * @return Paginator<UserAchievement>
     */
    public function get(array $params = []): Paginator;
}
