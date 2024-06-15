<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("master_kabupaten")->insert([
            [
                "provinsi_id"=> 17,
                "name"=> "Jakarta Pusat",
                "numbering"=> 1,
            ],
            [
                "provinsi_id"=> 17,
                "name"=> "Jakarta Barat",
                "numbering"=> 2,
            ],
            [
                "provinsi_id"=> 17,
                "name"=> "Jakarta Timur",
                "numbering"=> 3,
            ],
            [
                "provinsi_id"=> 17,
                "name"=> "Jakarta Utara",
                "numbering"=> 4,
            ],
            [
                "provinsi_id"=> 17,
                "name"=> "Jakarta Selatan",
                "numbering"=> 5,
            ],
        ]);
    }
}
