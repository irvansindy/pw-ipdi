<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories_events')->insert([
            [
                'name' => 'JNNS',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Seminar',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Resertifikasi',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'WKD',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kongres Wilayah',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]
        ]
        );
    }
}
