<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Selamat Datang di Portal Informasi Publik',
                'description' => 'Akses layanan, informasi berita, dan kirimkan pertanyaan Anda secara online.',
                'image' => 'slides/banner-welcome.jpg',
                'link_url' => '/posts',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Layanan Tanya Masyarakat Terbuka 24 Jam',
                'description' => 'Sampaikan aspirasi dan konsultasi Anda melalui formulir interaktif di aplikasi mobile.',
                'image' => 'slides/banner-tanya.jpg',
                'link_url' => '/tanya',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::firstOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
