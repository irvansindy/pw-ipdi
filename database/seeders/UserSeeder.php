<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@argon.com',
            'password' => 'secret'
            // 'password' => bcrypt('secret')
            // 'password' => Hash::make('secret')
        ]);

        $admin->assignRole('admin');
        
        $user = User::create([
            'username' => 'user',
            'firstname' => 'User',
            'lastname' => 'User',
            'email' => 'user@argon.com',
            'password' => 'secretuser'
            // 'password' => bcrypt('secretuser')
            // 'password' => Hash::make('secret')
        ]);

        $user->assignRole('user');
    }
}
