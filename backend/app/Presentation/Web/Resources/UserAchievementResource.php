<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAchievementResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
           'user' => UserResource::make($this->whenLoaded('user')),
           'achievement' => AchievementResource::make($this->whenLoaded('achievement')),
        ]);
    }
}
