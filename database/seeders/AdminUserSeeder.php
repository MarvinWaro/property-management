<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'CHED Administrator',
            'email' => 'admin@ched.com',
            'role' => 'admin',
            'status' => 1, // 1 for active
            'email_verified_at' => now(),
            'password' => Hash::make('hemisro12'), // Default admin password
        ]);
    }
}
