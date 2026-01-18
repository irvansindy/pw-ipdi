<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Events;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Update existing events dengan date dan date_end
     */
    public function run(): void
    {
        // Contoh: Update event yang sudah ada
        // Atau buat event baru untuk testing

        $events = [
            [
                'title' => 'Seminar Nasional Nefrologi 2025',
                'date' => Carbon::now()->addDays(1), // Mulai besok
                'date_end' => Carbon::now()->addDays(3), // Selesai 3 hari dari sekarang
                // total_days dan max_attendance akan auto-calculated
            ],
            [
                'title' => 'Workshop Dialisis 2 Hari',
                'date' => Carbon::now(),
                'date_end' => Carbon::now()->addDays(1),
            ],
            [
                'title' => 'Pelatihan Intensif 5 Hari',
                'date' => Carbon::now()->subDays(2),
                'date_end' => Carbon::now()->addDays(2),
            ],
            [
                'title' => 'Konferensi 1 Hari',
                'date' => Carbon::now(),
                'date_end' => null, // Null = event 1 hari
            ],
        ];

        foreach ($events as $eventData) {
            // Update event yang sudah ada berdasarkan title
            $event = Events::where('title', $eventData['title'])->first();

            if ($event) {
                $event->update([
                    'date' => $eventData['date'],
                    'date_end' => $eventData['date_end'],
                    // total_days dan max_attendance auto-calculated via model boot
                ]);

                $this->command->info("✓ Updated: {$eventData['title']}");
                $this->command->info("  - Date: {$event->date->format('d-m-Y')}");
                $this->command->info("  - Date End: " . ($event->date_end ? $event->date_end->format('d-m-Y') : 'Same day'));
                $this->command->info("  - Total Days: {$event->total_days}");
                $this->command->info("  - Max Attendance: {$event->max_attendance}");
                $this->command->line("");
            } else {
                $this->command->warn("⚠ Event not found: {$eventData['title']}");

                // Atau uncomment untuk create event baru
                // $newEvent = Events::create([
                //     'name' => $eventData['title'],
                //     'title' => $eventData['title'],
                //     'category_event_id' => 1, // Sesuaikan
                //     'price' => '0',
                //     'quota' => 100,
                //     'date' => $eventData['date'],
                //     'date_end' => $eventData['date_end'],
                //     'is_active' => 1,
                // ]);
                // $this->command->info("✓ Created: {$eventData['title']}");
            }
        }

        // Update semua event yang date_end masih null (set sama dengan date)
        $eventsWithoutEndDate = Events::whereNull('date_end')->whereNotNull('date')->get();

        foreach ($eventsWithoutEndDate as $event) {
            $event->update([
                'date_end' => $event->date, // Set date_end = date (event 1 hari)
            ]);
            $this->command->info("✓ Auto-set date_end for: {$event->title}");
        }

        $this->command->info("\n✅ Event dates seeded successfully!");
    }
}