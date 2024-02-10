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
            ],
            [
                'name' => 'Seminar',
            ],
            [
                'name' => 'Resertifikasi',
            ],
            [
                'name' => 'WKD',
            ],
            [
                'name' => 'Kongres Wilayah',
            ]
        ]
        );
    }
}
