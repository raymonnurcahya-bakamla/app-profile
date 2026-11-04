<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Berita Utama', 'description' => 'Informasi penting dan peristiwa terkini seputar instansi', 'sort_order' => 1],
            ['name' => 'Pengumuman', 'description' => 'Pemberitahuan resmi dan edaran untuk masyarakat', 'sort_order' => 2],
            ['name' => 'Layanan Publik', 'description' => 'Informasi terkait layanan dan administrasi kependudukan', 'sort_order' => 3],
            ['name' => 'Artikel & Edukasi', 'description' => 'Tulisan informatif, panduan, dan edukasi publik', 'sort_order' => 4],
            ['name' => 'Agenda Kegiatan', 'description' => 'Jadwal dan dokumentasi kegiatan instansi', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
