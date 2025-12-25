<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            // RoleSeeder::class,
            // UserSeeder::class,
            EventCategorySeeder::class,
            EventSeeder::class,
            TransactionSeeder::class,
            BankSeeder::class,
            EventDetailSeeder::class,
            EventHotelSeeder::class,
            HotelSeeder::class,
            VisiSeeder::class,
            MisiSeeder::class,
        ]);
    }
}