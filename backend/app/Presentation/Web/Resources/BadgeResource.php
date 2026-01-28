<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BadgeResource extends JsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'icon_url' => $this->whenHas('icon', $this->icon_url),
        ]);
    }
}
