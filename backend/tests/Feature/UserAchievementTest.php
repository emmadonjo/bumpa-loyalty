<?php

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('admin can retrieve achievements', function () {
    User::factory(10)->hasAttached(
        Achievement::factory()->count(random_int(1, 8)),
        ['unlocked_at' => now()]
    )
        ->create();

    $admin = User::factory()->admin()->create();
    Sanctum::actingAs($admin);

    $this->getJson('/api/admin/users/achievements')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta' => [
                'last_page',
                'current_page',
                'total',
            ]
        ]);
});

test('non-admin cannot retrieve achievements', function () {
    $user = User::factory()->customer()->create();
    Sanctum::actingAs($user);

    $this->getJson('/api/admin/users/achievements')
        ->assertForbidden();
});

test("admin can retrieve a customer's achievements", function () {
    $customer = User::factory()->customer()
        ->hasAttached(
        Achievement::factory()->count(8),
        ['unlocked_at' => now()]
    )
        ->create();

    $admin = User::factory()->admin()->create();
    Sanctum::actingAs($admin);

    $this->getJson("/api/users/{$customer->id}/achievements")
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'status',
            'message',
            'meta' => [
                'per_page',
                'total',
                'current_page',
                'last_page',
            ]
        ]);
});

test("user can retrieve their achievements", function () {
    $customer = User::factory()->customer()
        ->hasAttached(
            Achievement::factory()->count(8),
            ['unlocked_at' => now()]
        )
        ->create();

    Sanctum::actingAs($customer);
    $this->getJson("/api/users/{$customer->id}/achievements")
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'status',
            'message',
            'meta' => [
                'per_page',
                'total',
                'current_page',
                'last_page',
            ]
        ]);
});


test("user cannot retrieve other user's achievements", function () {
    $user = User::factory()->customer()->create();
    $customer = User::factory()->customer()->create();
    Sanctum::actingAs($user);
    $this->getJson("/api/users/{$customer->id}/achievements")
        ->assertForbidden();
});

test("guest cannot retrieve their achievements", function () {
    $customer = User::factory()->customer()->create();
    $this->getJson("/api/users/{$customer->id}/achievements")
        ->assertUnauthorized();
});
