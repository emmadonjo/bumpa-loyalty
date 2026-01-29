<?php

namespace App\Presentation\Web\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        unset($data['request_payload']);
        unset($data['response_payload']);
        return array_merge($data, [
            'user' => UserResource::make($this->whenLoaded('user')),
        ]);
    }
}
