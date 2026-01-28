<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
           'loyaltyInfo' => LoyaltyInfoResource::make($this->whenLoaded('loyaltyInfo')),
        ]);
    }
}
