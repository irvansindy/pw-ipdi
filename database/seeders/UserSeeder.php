<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'username' => 'Irvan Sindy', 
            'email' => 'superadmin@gmail.com',
            'password' => 'pass12345'
        ]);
        $superAdmin->assignRole('superadmin');

        // Creating Admin User
        $admin = User::create([
            'username' => 'admin biasa',
            'email' => 'adminbiasa@gmail.com',
            'password' => 'pass12345'
        ]);
        $admin->assignRole('admin');

        // Creating Product Manager User
        $user = User::create([
            'username' => 'user pertama', 
            'email' => 'userpertama@gmail.com',
            'password' => 'pass12345'
        ]);
        $user->assignRole('user');
    }
}
