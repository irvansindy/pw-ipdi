<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("master_provinsi")->insert([
            [
                "name"=> "Nanggroe Aceh Darussalam",
                "numbering"=> 1,
            ],
            [
                "name"=> "Sumatera Utara",
                "numbering"=> 2,
            ],
            [
                "name"=> "Sumatera Selatan",
                "numbering"=> 3,
            ],
            [
                "name"=> "Sumatera Barat",
                "numbering"=> 4,
            ],
            [
                "name"=> "Bengkulu",
                "numbering"=> 5,
            ],
            [
                "name"=> "Riau",
                "numbering"=> 6,
            ],
            [
                "name"=> "Kepulauan Riau",
                "numbering"=> 7,
            ],
            [
                "name"=> "Jambi",
                "numbering"=> 8,
            ],
            [
                "name"=> "Lampung",
                "numbering"=> 9,
            ],
            [
                "name"=> "Bangka Belitung",
                "numbering"=> 10,
            ],
            [
                "name"=> "Kalimantan Barat",
                "numbering"=> 11,
            ],
            [
                "name"=> "Kalimantan Timur",
                "numbering"=> 12,
            ],
            [
                "name"=> "Kalimantan Selatan",
                "numbering"=> 13,
            ],
            [
                "name"=> "Kalimantan Tengah",
                "numbering"=> 14,
            ],
            [
                "name"=> "Kalimantan Utara",
                "numbering"=> 15,
            ],
            [
                "name"=> "Banten",
                "numbering"=> 16,
            ],
            [
                "name"=> "DKI Jakarta",
                "numbering"=> 17,
            ],
            [
                "name"=> "Jawa Barat",
                "numbering"=> 18,
            ],
            [
                "name"=> "Jawa Tengah",
                "numbering"=> 19,
            ],
            [
                "name"=> "Daerah Istimewa Yogyakarta",
                "numbering"=> 20,
            ],
            [
                "name"=> "Jawa Timur",
                "numbering"=> 21,
            ],
            [
                "name"=> "Bali",
                "numbering"=> 22,
            ],
            [
                "name"=> "Nusa Tenggara Timur",
                "numbering"=> 23,
            ],
            [
                "name"=> "Nusa Tenggara Barat",
                "numbering"=> 24,
            ],
            [
                "name"=> "Gorontalo",
                "numbering"=> 25,
            ],
            [
                "name"=> "Sulawesi Barat",
                "numbering"=> 26,
            ],
            [
                "name"=> "Sulawesi Tengah",
                "numbering"=> 27,
            ],
            [
                "name"=> "Sulawesi Utara",
                "numbering"=> 28,
            ],
            [
                "name"=> "Sulawesi Tenggara",
                "numbering"=> 29,
            ],
            [
                "name"=> "Sulawesi Selatan",
                "numbering"=> 30,
            ],
            [
                "name"=> "Maluku Utara",
                "numbering"=> 31,
            ],
            [
                "name"=> "Maluku",
                "numbering"=> 32,
            ],
            [
                "name"=> "Papua Barat",
                "numbering"=> 33,
            ],
            [
                "name"=> "Papua",
                "numbering"=> 34,
            ],
            [
                "name"=> "Papua Pegunungan",
                "numbering"=> 35,
            ],
            [
                "name"=> "Papua Selatan",
                "numbering"=> 36,
            ],
            [
                "name"=> "Papua Barat Daya",
                "numbering"=> 37,
            ],
        ]);
    }
}
