<?php

use App\Domains\Accounts\Persistence\Entities\User;
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

test('successful purchase', function () {
    $reference = "reference_test_12345";
    $accessCode = 'access_code_test_1234';
    $checkoutUrl = "https://checkout.paystack.com/{$accessCode}";

    Http::fake([
        "https://api.paystack.co/*" => Http::response([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => $checkoutUrl,
                'access_code' => $accessCode,
                'reference' => $reference,
            ]
        ], 200)
    ]);

    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', ['amount' => 1_000])
        ->assertOk()
        ->assertJson([
            'status' => true,
            'message' => 'Payment link generated',
            'data' => [
                'checkout_url' => $checkoutUrl,
            ]
        ]);

    Http::assertSent(function ($request) use ($user) {
        return str_contains($request->url(), '/transaction/initialize')
            && $request->method() === 'POST'
            && $request->data()['email'] === $user->email
            && $request->data()['amount'] == 1_000 * 100;
    });
    $this->assertDatabaseCount('purchases', 1);
});

test('failed purchase: api response', function () {
    Http::fake([
        "https://api.paystack.co/*" => Http::response([
            'status' => true,
            'message' => 'An error occurred',
        ], 400)
    ]);

    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', ['amount' => 1_000])
        ->assertInternalServerError();
});

test('failed purchase: no checkout url', function () {
    $reference = "reference_test_12345";
    $accessCode = 'access_code_test_1234';

    Http::fake([
        "https://api.paystack.co/*" => Http::response([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => '',
                'access_code' => $accessCode,
                'reference' => $reference,
            ]
        ], 200)
    ]);

    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', ['amount' => 1_000])
        ->assertStatus(502)
        ->assertJson([
            'status' => false,
            'message' => 'Payment link could not be generated',
            'data' => []
        ]);
});

test('guest cannot purchase', function () {
    $this->postJson('/api/users/purchases')
        ->assertUnauthorized();
});

test('amount required to purchase', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors('amount');
});

test('amount cannot not be lesser than 100', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', ['amount' =>99.99])
        ->assertStatus(422)
        ->assertJsonValidationErrors('amount');
});

test('amount cannot not be greater than 500000', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $this->postJson('/api/users/purchases', ['amount' => 500_000.01])
        ->assertStatus(422)
        ->assertJsonValidationErrors('amount');
});


