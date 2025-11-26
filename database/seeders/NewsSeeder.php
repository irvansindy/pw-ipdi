<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use App\Models\Events;
use Carbon\Carbon;
class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::first(); // Atau buat user baru jika belum ada
        $event = Events::first(); // Atau buat event baru jika belum ada

        $newsData = [
            [
                'title' => 'World Kidney Day Maret 2024',
                'content' => 'Jalan Santai Bersama PW IPDI DKI dan Anggota IPDI DKI saat CFD di Monas. Seminar Awam bersama Pasien Dialisis di RS.St Carolus. Pemberian Bunga Sebagai Simbolis Mengkampanyekan Ginjal Sehat. Penyerahan Hadiah Pemenang 1 Lomba Video Edukasi dan Kreasi Dekorasi oleh Panitia WKD ke RS St.Carolus.',
                'image' => 'img/carousel/WKD Maret 2024.png',
                'author_id' => $author?->id,
                'event_id' => $event?->id,
                'status' => 'published',
                'published_at' => Carbon::parse('2024-03-14'),
            ],
            [
                'title' => 'PITWIL IPDI DKJ - Symposium XVI',
                'content' => 'Jakarta Nephrology Nursing Symposium XVI dilaksanakan pada 27-28 Juli 2024 dengan tema "Enhancing Professional Care in Dialysis Nursing Through The Current Technology". Event ini menghadirkan pembicara berkualitas dan workshop praktis untuk meningkatkan kompetensi perawat dialisis.',
                'image' => 'img/carousel/WKD Maret 2024.png',
                'author_id' => $author?->id,
                'event_id' => $event?->id,
                'status' => 'published',
                'published_at' => Carbon::parse('2024-07-27'),
            ],
            [
                'title' => 'World Kidney Day Maret 2025',
                'content' => 'Peringatan Hari Ginjal Sedunia 2025 dengan tema "A single drop of your blood can be a lifetime for someone else." Kegiatan meliputi donor darah, edukasi kesehatan ginjal, dan pemeriksaan kesehatan gratis.',
                'image' => 'img/carousel/WKD Maret 2024.png',
                'author_id' => $author?->id,
                'event_id' => $event?->id,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-03-13'),
            ],
            [
                'title' => 'Resertifikasi Dialisis Batch 1 & 2 Tahun 2025',
                'content' => 'Program resertifikasi dialisis untuk meningkatkan kompetensi perawat dialisis sesuai standar nasional dan internasional. Dilaksanakan dalam 2 batch dengan materi komprehensif mencakup teori dan praktik.',
                'image' => 'img/carousel/WKD Maret 2024.png',
                'author_id' => $author?->id,
                'event_id' => $event?->id,
                'status' => 'published',
                'published_at' => Carbon::parse('2025-01-15'),
            ],
        ];

        foreach ($newsData as $news) {
            News::create($news);
        }
    }
}
