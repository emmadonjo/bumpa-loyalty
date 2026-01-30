<?php

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Loyalty\Services\RewardsService;
use App\Domains\Store\Persistence\Entities\Purchase;
use App\Infrastructure\Messaging\Events\BadgeUnlocked;
use App\Presentation\Web\DataTransferObjects\PurchaseDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


test('achievement unlocked', function () {
    Artisan::call('db:seed AchievementSeeder');
    Artisan::call('db:seed BadgeSeeder');

    $user = User::factory()
        ->customer()
        ->has(Purchase::factory()->count(1))
        ->create();

    $purchase = $user->purchases()->first();

    $service = resolve(RewardsService::class);
    $service->handle(PurchaseDto::fromArray([
        'customer_id' => $user->id,
        'amount' => $purchase->amount,
        'reference' => $purchase->reference,
        'provider' => $purchase->provider,
        'purchase_date' => $purchase->purchased_at,
        'currency' => $purchase->currency,
    ]));

    $this->assertDatabaseHas('user_achievements', [
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseHas('loyalty_trackers', [
        'user_id' => $user->id,
        'purchase_count' => 1
    ]);
});

test('badge unlocked', function () {
    Artisan::call('db:seed AchievementSeeder');
    Artisan::call('db:seed BadgeSeeder');

    $user = User::factory()
        ->customer()
        ->has(Purchase::factory()->count(1))
        ->create();

    $purchase = $user->purchases()->first();
    Event::fake();

    $service = resolve(RewardsService::class);
    $service->handle(PurchaseDto::fromArray([
        'customer_id' => $user->id,
        'amount' => $purchase->amount,
        'reference' => $purchase->reference,
        'provider' => $purchase->provider,
        'purchase_date' => $purchase->purchased_at,
        'currency' => $purchase->currency,
    ]));

    $this->assertDatabaseHas('user_badges', [
        'user_id' => $user->id,
    ]);

    $this->assertGreaterThan(0, $user->badges()->count());

    $this->assertDatabaseHas('loyalty_trackers', [
        'user_id' => $user->id,
        'purchase_count' => 1
    ]);

    Event::assertDispatched(BadgeUnlocked::class);
});


test('not customer found for purchase event', function () {
    $user = User::factory()
        ->customer()
        ->has(Purchase::factory()->count(1))
        ->create();

    $purchase = $user->purchases()->first();

    $service = resolve(RewardsService::class);
    $service->handle(PurchaseDto::fromArray([
        'customer_id' => 100,
        'amount' => $purchase->amount,
        'reference' => $purchase->reference,
        'provider' => $purchase->provider,
        'purchase_date' => $purchase->purchased_at,
        'currency' => $purchase->currency,
    ]));

    $this->assertDatabaseMissing('user_achievements', [
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseMissing('loyalty_trackers', [
        'user_id' => $user->id,
        'purchase_count' => 1
    ]);

    $this->assertDatabaseMissing('user_achievements', [
        'user_id' => $user->id,
    ]);
})->throws(ModelNotFoundException::class, "No user was found for ID 100");
