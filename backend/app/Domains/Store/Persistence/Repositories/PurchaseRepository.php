<?php

namespace App\Domains\Store\Persistence\Repositories;

use App\Domains\Store\Persistence\Contracts\PurchaseRepositoryInterface;
use App\Domains\Store\Persistence\Entities\Purchase;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    /**
     * Save a new record to storage
     * @param array $attributes
     * @return Purchase
     */
    public function save(array $attributes): Purchase
    {
        return Purchase::create($attributes);
    }

    /**
     * retrieve a record by reference
     * @param string $reference
     * @return Purchase|null
     */
    public function findByReference(string $reference): ?Purchase
    {
        return Purchase::where('reference', $reference)->first();
    }
}
