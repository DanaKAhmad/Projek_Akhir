<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if (!User::where('email', 'tokoh@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'tokoh@gmail.com',
                'password' => Hash::make('passwordadmin'),
                'role' => 'admin',
            ]);
        }
    }
    
}
