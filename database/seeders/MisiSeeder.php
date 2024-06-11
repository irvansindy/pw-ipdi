<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("misions")->insert([
            [
                "content"=> "Mengembangkan program pelatihan dan pengembangan berkelanjutan untuk meningkatkan kompetensi, profesionalisme dan etik kerja anggota IPDI DKI Jakarta.",
                "ordering" => 1,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Mendorong kultur jujur, tanggung jawab, dan disiplin dalam setiap aspek keanggotaan.",
                "ordering" => 2,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Menjalin kemitraan strategis dengan Pemerintah, Institusi Pendidikan, Center Dialisis dan Lembaga atau Organisasi terkait, guna meningkatkan kualifikasi dan keterampilan perawat dialysis.",
                "ordering" => 3,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Menyelenggarakan penelitian dalam bidang kesehatan ginjal untuk meningkatkan pemahaman dan penanganan komplikasi dialysis di Center Dialisis maupun di rumah.",
                "ordering" => 4,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Menginisiasi program pengabdian masyarakat yang berfokus pada pencegahan penyakit ginjal dan peningkatan kesehatan ginjal diberbagai lapisan masyarakat.",
                "ordering" => 5,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Berpartisipasi dalam Kampanye Nasional dan Internasional yang mendukung upaya pencegahan dan penanganan penyakit ginjal.",
                "ordering" => 6,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Menyediakan fasilitas dan sumber daya yang mendukung perlindungan hukum bagi anggota IPDI DKI Jakarta bekerjasama dengan PP IPDI dan PPNI.",
                "ordering" => 7,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
            [
                "content"=> "Mengelola sumber daya organisasi secara efisien dan transparan untuk mendukung kelancaran organisasi.",
                "ordering" => 8,
                "created_at"=> now()->format("Y-m-d H:i:s"),
            ],
        ]);
    }
}
