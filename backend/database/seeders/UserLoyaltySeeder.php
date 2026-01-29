<?php

namespace Database\Seeders;

use App\Domains\Accounts\Persistence\Entities\User;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use App\Domains\Loyalty\Persistence\Entities\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLoyaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::chunkById(10, function (Collection $users) {
            foreach ($users as $user) {
                DB::transaction(function () use ($user) {
                    $take = random_int(1, 5);

                    $achievements = Achievement::take($take)->get();
                    foreach ($achievements as $achievement) {
                        $user->achievements()->attach([$achievement->id => [
                            'unlocked_at' => now()->subDays(random_int(1, 10)),
                        ]]);
                    }

                    $currentBadgeId = null;

                    $badges = Badge::get();
                    foreach ($badges as $badge) {
                        if ($take >= $badge->achievements_required) {
                            $user->badges()->attach([$badge->id => [
                                'awarded_at' => now(),
                            ]]);
                            $currentBadgeId = $badge->id;
                        }
                    }

                    $totalSpent = random_int(1_000, 10_000);
                    $user->loyaltyInfo()->updateOrCreate([
                        'user_id' => $user->id,
                    ],[
                        'purchase_count' => $take,
                        'total_spent' => $totalSpent,
                        'payout_balance' => $achievements->sum('reward'),
                        'current_badge_id' => $currentBadgeId,
                    ]);
                });
            }
        });
    }
}
