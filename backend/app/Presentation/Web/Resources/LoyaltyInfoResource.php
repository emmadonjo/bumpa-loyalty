<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoyaltyInfoResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'currentBadge' => BadgeResource::make($this->whenLoaded('currentBadge')),
        ]);
    }
}
