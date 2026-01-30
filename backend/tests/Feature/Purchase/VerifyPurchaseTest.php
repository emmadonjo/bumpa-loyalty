<?php

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Store\Enums\PurchaseStatus;
use App\Domains\Store\Persistence\Entities\Purchase;
use App\Infrastructure\Messaging\Events\AchievementUnlocked;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'services.paystack.base_url' => 'https://api.paystack.co',
        'services.paystack.api_key' => 'sk_6873ahsha389usausajhshja',
        'services.paystack.payment_channels' => 'card',
    ]);
});

test('verify purchase', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();

    Http::fake([
        "https://api.paystack.co/transaction/verify/*" => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'id' => random_int(10_000_000_000, 99_999_9999_9999),
                'domain' => 'test',
                'status' => 'success',
                'reference' => $purchase->reference,
                'amount' => $purchase->amount * 100,
                'paid_at' => '2024-08-22T09:15:02.000Z',
                'created_at' => '2024-08-22T09:14:24.000Z',
                'channel' => 'card',
                'currency' => 'NGN',
                'metadata' => [
                    'customer_id' => $user->id,
                ],
                'fees' => $purchase->fees * 100,
            ]
        ])
    ]);

    Event::fake();

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/verify")
        ->assertSuccessful()
        ->assertJson([
            'status' => true,
            'message' => 'Payment was successful',
            'data' => [
                'amount' => $purchase->amount,
                'payment_method' => 'card',
                'status' => 'successful',
            ]
        ]);

    Http::assertSent(function ($request) use ($purchase) {
        return str_contains($request->url(), "/transaction/verify/{$purchase->reference}")
            && $request->method() === 'GET';
    });

    Event::assertDispatched(AchievementUnlocked::class);
});


test('verify purchase - invalid reference', function () {
    $user = User::factory()->customer()
        ->create();

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/invalid/verify")
        ->assertNotFound()
        ->assertJson([
            'message' => 'No payment record with the reference "invalid"',
        ]);
});

test('verify purchase - purchase already verified', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();
    $purchase->update([
        'status' => PurchaseStatus::SUCCESSFUL,
        'payment_method' => 'card',
    ]);

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/verify")
        ->assertSuccessful()
        ->assertJson([
            'status' => true,
            'message' => 'Payment was successful',
            'data' => [
                'amount' => $purchase->amount,
                'payment_method' => 'card',
                'status' => 'successful',
            ]
        ]);
});

test('verify purchase - purchase already abandoned', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();
    $purchase->update([
        'status' => PurchaseStatus::ABANDONED,
    ]);

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/verify")
        ->assertBadRequest()
        ->assertJson([
            'status' => false,
            'message' => 'Payment was not successful.',
            'data' => []
        ]);
});


test('verify purchase - failed API response', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();

    Http::fake([
        "https://api.paystack.co/transaction/verify/*" => Http::response([
            'status' => false,
            'message' => 'Verification successful',
            'data' => [
                'id' => random_int(10_000_000_000, 99_999_9999_9999),
                'domain' => 'test',
                'status' => 'success',
                'reference' => $purchase->reference,
                'amount' => $purchase->amount * 100,
                'paid_at' => '2024-08-22T09:15:02.000Z',
                'created_at' => '2024-08-22T09:14:24.000Z',
                'channel' => 'card',
                'currency' => 'NGN',
                'metadata' => [
                    'customer_id' => $user->id,
                ],
                'fees' => $purchase->fees * 100,
            ]
        ])
    ]);

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/verify")
        ->assertBadRequest()
        ->assertJson([
            'status' => false,
            'message' => 'Payment verification failed',
            'data' => []
        ]);

    Http::assertSent(function ($request) use ($purchase) {
        return str_contains($request->url(), "/transaction/verify/{$purchase->reference}")
            && $request->method() === 'GET';
    });
});


test('verify purchase - pending API response', function () {
    $user = User::factory()->customer()
        ->has(Purchase::factory()->count(1), 'purchases')
        ->create();

    /** @var Purchase $purchase */
    $purchase = $user->purchases()->first();

    Http::fake([
        "https://api.paystack.co/transaction/verify/*" => Http::response([
            'status' => true,
            'message' => 'Verification successful',
            'data' => [
                'id' => random_int(10_000_000_000, 99_999_9999_9999),
                'domain' => 'test',
                'status' => 'pending',
                'reference' => $purchase->reference,
                'amount' => $purchase->amount * 100,
                'paid_at' => '2024-08-22T09:15:02.000Z',
                'created_at' => '2024-08-22T09:14:24.000Z',
                'channel' => 'card',
                'currency' => 'NGN',
                'metadata' => [
                    'customer_id' => $user->id,
                ],
                'fees' => $purchase->fees * 100,
            ]
        ])
    ]);

    Sanctum::actingAs($user);
    $this->postJson("/api/payments/{$purchase->reference}/verify")
        ->assertOk()
        ->assertJson([
            'status' => true,
            'message' => 'Payment is still being processed.',
            'data' => [
                'amount' => $purchase->amount,
                'payment_method' => null,
                'status' => 'pending',
            ]
        ]);

    Http::assertSent(function ($request) use ($purchase) {
        return str_contains($request->url(), "/transaction/verify/{$purchase->reference}")
            && $request->method() === 'GET';
    });
});
