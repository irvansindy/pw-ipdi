<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("transactions")->insert([
            "number" => "TR001",
            "user_id" => 1,
            "event_id" => 1,
            "date" => now()->format('Y-m-d H:i:s'),
            "status" => 'pending',
        ]);
    }
}
