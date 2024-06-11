<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("visions")->insert([
            [
                "content"=> "Menjadikan PW IPDI DKI Jakarta sebagai pionir dalam memajukan Pendidikan, Penelitian, dan 
Pengembangan Organisasi, serta menjadi Pusat Informasi Center Dialysis Terdepan dalam memberikan 
Pelayanan Dialisis.",
            ]
        ]);
    }
}
