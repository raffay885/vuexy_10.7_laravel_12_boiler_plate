<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['first_name' => 'Super', 'last_name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => '12345678', 'user_type' => 'admin', 'role' => 'Super Admin'],
        ];

        foreach ($users as $user) {
            $user = User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'user_type' => $user['user_type'],
                    'password' => Hash::make($user['password']),
                ]
            );

            $user->assignRole($user['role'] ?? 'Super Admin');
        }
    }
}
