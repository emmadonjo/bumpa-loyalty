<?php

use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('non-admin cannot retrieve customers', function () {
    $user = User::factory()->customer()->create();
    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/admin/users');
    $response->assertForbidden();
});


test('guest admin cannot retrieve customers', function () {
    $response = $this->getJson('/api/admin/users');
    $response->assertUnauthorized();
});

test('admin can retrieve customers', function () {
    $user = User::factory()->admin()->create();
    User::factory(40)->customer()->create();
    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/admin/users');
    $response->assertOk();
    $response->assertJsonCount(20, 'data');
    $response->assertJson([
        'meta' => [
            'last_page' => 2
        ]
    ]);
});

test("admin can retrieve a customer's info", function () {
    $customer = User::factory()->customer()->create();
    $admin = User::factory()->admin()->create();
    Sanctum::actingAs($admin, ['*']);
    $this->getJson("/api/users/{$customer->id}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'role' => $customer->role->value,
            ]
        ]);
});

test("Customers can retrieve their own info", function () {
    $customer = User::factory()->customer()->create();

    Sanctum::actingAs($customer, ['*']);
    $this->getJson("/api/users/{$customer->id}")
        ->assertOk()
        ->assertJson([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'role' => $customer->role->value,
            ]
        ]);
});
