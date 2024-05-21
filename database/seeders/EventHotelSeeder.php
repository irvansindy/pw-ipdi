<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EventHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("event_hotels")->insert([
            [
                "event_id"=> 1,
                "name"=> "Hotel Ciputra",
                "price"=> "500000",
                "location"=> "Jakarta Barat",
            ],
        ]);
    }
}
