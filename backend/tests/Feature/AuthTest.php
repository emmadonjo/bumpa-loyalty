<?php

use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('customer can log in', function () {
    $user = User::factory()->create();
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            'user',
            'token',
        ]
    ]);

    $this->assertAuthenticatedAs($user);
});

test('invalid log in credentials', function () {
    $user = User::factory()->create();
    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'invalid',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'Incorrect email/password combination',
    ]);
});

test('authenticated user can log out', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $this->postJson('/api/logout')
        ->assertStatus(200)
        ->assertJson([
            'status' => true,
            'message' => 'Logout successful',
            'data' => []
        ]);

    $this->assertCount(0, $user->tokens);
});
