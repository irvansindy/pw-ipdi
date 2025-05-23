<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserVerificatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $verificator = User::create([
            'username' => 'Admin Verifikator', 
            'email' => 'pwipdiverifikator@gmail.com',
            'password' => 'qpwoei@132'
        ]);

        $verificator->assignRole('admin');
    }
}
