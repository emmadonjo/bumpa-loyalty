<?php

namespace Database\Seeders;

use App\Domains\Loyalty\Persistence\Entities\Badge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            ['name' => 'Initiator', 'icon' => 'icons/initiator.png', 'achievements_required' => 1],
            ['name' => 'Steady shopper', 'icon' => 'icons/steady-shopper.png', 'achievements_required' => 3],
            ['name' => 'High Roller', 'icon' => 'icons/high-roller.png', 'achievements_required' => 5],
            ['name' => 'Brand advocate', 'icon' => 'icons/brand-advocate.png', 'achievements_required' => 10],
            ['name' => 'Streak Keeper', 'icon' => 'icons/streak-keeper.png', 'achievements_required' => 20],
            ['name' => 'Cart warrior', 'icon' => 'icons/cart-warrior.png', 'achievements_required' => 50],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
