<?php

namespace Database\Seeders;

use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            ['name' => 'Layanan Kependudukan', 'description' => 'Pertanyaan seputar dokumen, identitas kependudukan, dan legalisir', 'sort_order' => 1],
            ['name' => 'Informasi & Keterbukaan Publik', 'description' => 'Permintaan data, dokumen publik, dan informasi instansi', 'sort_order' => 2],
            ['name' => 'Pengaduan & Fasilitas', 'description' => 'Laporan kendala fasilitas publik, jaringan, atau sarana kerja', 'sort_order' => 3],
            ['name' => 'Program & Bantuan', 'description' => 'Pertanyaan terkait sosialisasi program kerja dan bantuan pembinaan', 'sort_order' => 4],
            ['name' => 'Lainnya', 'description' => 'Topik umum di luar kategori utama', 'sort_order' => 5],
        ];

        foreach ($topics as $t) {
            QuestionTopic::firstOrCreate(
                ['slug' => Str::slug($t['name'])],
                [
                    'name' => $t['name'],
                    'description' => $t['description'],
                    'sort_order' => $t['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
