<?php

namespace App\Domains\Store\Persistence\Contracts;

use App\Domains\Store\Persistence\Entities\Purchase;

interface PurchaseRepositoryInterface
{
    /**
     * Save purchase record to storage
     * @param array $attributes
     * @return Purchase
     */
    public function save(array $attributes): Purchase;

    /**
     * retrieve a record by reference column
     * @param string $reference
     * @return Purchase|null
     */
    public function findByReference(string $reference): ?Purchase;
}
