<?php

namespace Database\Seeders;

use App\Domains\Accounts\Enums\UserRole;
use App\Domains\Accounts\Persistence\Entities\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create an admin user
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ],[
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => UserRole::ADMIN->value,
            'password' => Hash::make('password'),
        ]);
        $admin->markEmailAsVerified();

        // create a customer user
        $admin = User::updateOrCreate([
            'email' => 'customer@example.com',
        ],[
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'role' => UserRole::CUSTOMER->value,
            'password' => Hash::make('password'),
        ]);
        $admin->markEmailAsVerified();
    }
}
