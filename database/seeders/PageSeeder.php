<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
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

        $pages = [
            [
                'title' => 'Tentang Kami',
                'content' => '<h2>Profil Singkat</h2><p>Dinas Komunikasi dan Informatika mempunyai tugas menyelenggarakan urusan pemerintahan di bidang komunikasi dan informatika, persandian, dan statistik.</p><p>Kami berdedikasi untuk memberikan pelayanan prima dalam hal keterbukaan informasi dan transformasi digital.</p>',
                'status' => 'published',
                'sort_order' => 1,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Visi dan Misi',
                'content' => '<h2>Visi</h2><p>"Terwujudnya Tata Kelola Pemerintahan Berbasis Digital yang Terbuka, Handal, dan Melayani."</p><h2>Misi</h2><ol><li>Meningkatkan infrastruktur digital dan konektivitas antarlayanan publik.</li><li>Mewujudkan sistem keterbukaan informasi publik yang cepat, akurat, dan transparan.</li><li>Membangun ekosistem literasi digital bagi masyarakat.</li></ol>',
                'status' => 'published',
                'sort_order' => 2,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Struktur Organisasi',
                'content' => '<h2>Bagan Struktur Organisasi</h2><p>Dipimpin oleh Kepala Dinas dan dibantu oleh Sekretaris Dinas serta Kepala-Kepala Bidang teknis yang berfokus pada Pengelolaan Informasi, Infrastruktur Jaringan, dan Pemberdayaan Komunikasi Publik.</p>',
                'status' => 'published',
                'sort_order' => 3,
                'show_in_menu' => true,
            ],
            [
                'title' => 'Maklumat Pelayanan',
                'content' => '<h2>Maklumat Pelayanan Publik</h2><p>"Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar pelayanan yang telah ditetapkan dan siap menerima sanksi apabila pelayanan yang diberikan tidak sesuai standar."</p>',
                'status' => 'published',
                'sort_order' => 4,
                'show_in_menu' => false,
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => Str::slug($p['title'])],
                array_merge($p, ['user_id' => $user->id])
            );
        }
    }
}
