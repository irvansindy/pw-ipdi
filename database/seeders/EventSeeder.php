<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Events;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Events::create([
            'name' => 'Event JNNS',
            'title' => 'Event JNNS',
            'category_event_id' => 1,
            'price' => '500000',
            'last_register' => '2024-03-10',
            'description' => 'acara pertama untuk JNNS',
            'quota' => 200,
            'date' => '2024-03-20 10:00:00',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);

        Events::create([
            'name' => 'Event Seminar',
            'title' => 'Event Seminar',
            'category_event_id' => 2,
            'price' => '500000',
            'last_register' => '2024-04-10',
            'description' => 'acara pertama untuk Seminar',
            'quota' => 200,
            'date' => '2024-04-20 10:00:00',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        Events::create([
            'name' => 'Event Resertifikasi',
            'title' => 'Event Resertifikasi',
            'category_event_id' => 1,
            'price' => '500000',
            'last_register' => '2024-05-10',
            'description' => 'acara pertama untuk Resertifikasi',
            'quota' => 200,
            'date' => '2024-05-20 10:00:00',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        Events::create([
            'name' => 'Event WKD',
            'title' => 'Event WKD',
            'category_event_id' => 1,
            'price' => '500000',
            'last_register' => '2024-06-10',
            'description' => 'acara pertama untuk WKD',
            'quota' => 200,
            'date' => '2024-06-20 10:00:00',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);
        
        Events::create([
            'name' => 'Event Kongres Wilayah',
            'title' => 'Event Kongres Wilayah',
            'category_event_id' => 1,
            'price' => '500000',
            'last_register' => '2024-07-10',
            'description' => 'acara pertama untuk Kongres Wilayah',
            'quota' => 200,
            'date' => '2024-07-20 10:00:00',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ]);

    }
}
