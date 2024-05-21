<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("hotels")->insert([
            [
                "name"=> "Hotel Ciputra",
                "price_per_night"=> "500000",
                "location"=> "Jakarta Barat",
            ]
        ]);
    }
}
