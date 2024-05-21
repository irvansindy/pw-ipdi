<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EventDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("event_details")->insert([
            [
                "event_id"=> 1,
                "name"=> "Simposium",
                "price"=> "400000",
            ],
            [
                "event_id"=> 1,
                "name"=> "Workshop",
                "price"=> "400000",
            ],
            [
                "event_id"=> 1,
                "name"=> "Paket",
                "price"=> "700000",
            ],
        ]);
    }
}
