<?php

namespace Database\Seeders;

use app\Domains\Loyalty\Enums\AchievementType;
use App\Domains\Loyalty\Persistence\Entities\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [ 'name' => "First Purchase", 'type' => AchievementType::PURCHASE_COUNT,
                'threshold' => 1,
                'reward' => 50,
                'description' => 'Purchase your first product',
            ],
            [ 'name' => "Ranger", 'type' => AchievementType::PURCHASE_COUNT,
                'threshold' => 5,
                'reward' => 100,
                'description' => 'make purchases 5 times',
            ],
            [
                'name' => "Evolve",
                'type' => AchievementType::TOTAL_SPEND,
                'threshold' => 10_000,
                'reward' => 200,
                'description' => 'Make a cumulative spend of N10,000',
            ],
            [
                'name' => "Locked in",
                'type' => AchievementType::PURCHASE_COUNT,
                'threshold' => 20,
                'reward' => 500,
                'description' => 'Make 20 purchases',
            ],
            [
                'name' => "Inevitable",
                'type' => AchievementType::PURCHASE_COUNT,
                'threshold' => 100_000,
                'reward' => 1000,
                'description' => 'Make a cumulative spend of  N100,000',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
