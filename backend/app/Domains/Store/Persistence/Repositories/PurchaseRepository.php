<?php

namespace App\Domains\Store\Persistence\Repositories;

use App\Domains\Store\Persistence\Contracts\PurchaseRepositoryInterface;
use App\Domains\Store\Persistence\Entities\Purchase;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function save(array $attributes): Purchase
    {
        return Purchase::create($attributes);
    }
}
