<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
           'loyaltyInfo' => LoyaltyInfoResource::make($this->whenLoaded('loyaltyInfo')),
            'achievements_count' => $this->whenCounted('achievements'),
            'badges' => BadgeResource::collection($this->whenLoaded('badges')),
            'badges_count' => $this->whenCounted('badges'),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
//            'all_badges' => BadgeResource::collection($this->whenLoaded('all_badges')),
        ]);
    }
}
