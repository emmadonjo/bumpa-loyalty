<?php

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Store\Persistence\Entities\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('Cancel purchase', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/cancel")
        ->assertOk()
        ->assertJson([
            'status' => true,
            'message' => 'Payment has been cancelled',
            'data' => []
        ]);
});
