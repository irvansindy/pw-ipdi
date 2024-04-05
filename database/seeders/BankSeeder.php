<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banks')->insert([
            [
                'name' => 'BNI',
                'account_name' => 'PW IPDI DKI JAKARTA Cabang UI Depok',
                'account_number' => '1811935027',
                'created_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
