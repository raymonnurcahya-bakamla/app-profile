<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@instansi.go.id',
            'role' => 'admin',
        ]);

        $category = Category::first();

        $samplePosts = [
            [
                'title' => 'Peluncuran Portal Layanan Informasi Publik Digital',
                'summary' => 'Instansi resmi meluncurkan portal terpadu untuk mempermudah masyarakat mengakses informasi dan menyampaikan pertanyaan.',
                'content' => '<p>Dalam rangka meningkatkan transparansi dan kemudahan akses bagi masyarakat, hari ini resmi diluncurkan portal informasi publik terpadu.</p><p>Masyarakat kini dapat mengajukan pertanyaan, memantau pengumuman, dan memperoleh data publik secara langsung melalui website dan aplikasi mobile.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'is_featured' => true,
                'view_count' => 125,
            ],
            [
                'title' => 'Sosialisasi Tata Cara Pengajuan Aspirasi dan Pengaduan',
                'summary' => 'Panduan lengkap bagi masyarakat yang ingin menyampaikan pertanyaan atau laporan kepada instansi.',
                'content' => '<p>Aspirasi dan pertanyaan dari masyarakat merupakan masukan berharga bagi peningkatan mutu pelayanan kami. Melalui fitur formulir online, seluruh pertanyaan akan diteruskan ke tim terkait dan dijawab dalam waktu maksimal 3 hari kerja.</p>',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'is_featured' => false,
                'view_count' => 84,
            ],
            [
                'title' => 'Jadwal Layanan Tatap Muka Selama Bulan Ini',
                'summary' => 'Pemberitahuan mengenai jam operasional kantor layanan dan pembagian loket konsultasi.',
                'content' => '<p>Layanan tatap muka dibuka setiap hari kerja mulai pukul 08.00 hingga 15.00 WIB. Disarankan bagi masyarakat untuk membawa identitas diri yang masih berlaku.</p>',
                'status' => 'published',
                'published_at' => now(),
                'is_featured' => false,
                'view_count' => 45,
            ],
        ];

        foreach ($samplePosts as $post) {
            Post::firstOrCreate(
                ['slug' => Str::slug($post['title'])],
                array_merge($post, [
                    'user_id' => $user->id,
                    'category_id' => $category?->id,
                ])
            );
        }
    }
}
