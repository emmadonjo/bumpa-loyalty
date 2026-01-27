<?php

namespace Database\Seeders;

use app\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeer::class,
            AchievementSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
