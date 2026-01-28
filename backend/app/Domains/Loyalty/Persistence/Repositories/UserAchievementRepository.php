<?php

namespace App\Domains\Loyalty\Persistence\Repositories;

use App\Domains\Loyalty\Persistence\Contracts\UserAchievementRepositoryInterface;
use App\Domains\Loyalty\Persistence\Entities\UserAchievement;
use Illuminate\Contracts\Pagination\Paginator;

class UserAchievementRepository implements UserAchievementRepositoryInterface
{
    /**
     * @param array $params
     * @return Paginator<UserAchievement>
     */
    public function get(array $params = []): Paginator
    {
        $search = data_get($params, 'search');
        $type = data_get($params, 'type');
        $perPage = data_get($params, 'per_page', 10);
        $user_id = data_get($params, 'user_id');

        return UserAchievement::with(['user', 'achievement'])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('achievement', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($type, function ($query, $type) {
                $query->whereHas('achievement', function ($query) use ($type) {
                    $query->where('type', $type);
                });
            })
            ->when($user_id, function ($query, $user_id) {
                $query->whereHas('user', function ($query) use ($user_id) {
                    $query->where('id', $user_id);
                });
            })
            ->simplePaginate((int)$perPage);
    }
}
