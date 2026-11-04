<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headerMenu = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Menu Utama Header', 'is_active' => true]
        );

        $items = [
            ['label' => 'Beranda', 'url' => '/', 'type' => 'custom', 'sort_order' => 1],
            ['label' => 'Tentang Kami', 'url' => '/tentang-kami', 'type' => 'page', 'sort_order' => 2],
            ['label' => 'Berita', 'url' => '/posts', 'type' => 'post', 'sort_order' => 3],
            ['label' => 'Tanya Kami', 'url' => '/tanya', 'type' => 'custom', 'sort_order' => 4],
            ['label' => 'FAQ', 'url' => '/faq', 'type' => 'custom', 'sort_order' => 5],
        ];

        foreach ($items as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'label' => $item['label']],
                array_merge($item, ['menu_id' => $headerMenu->id, 'is_active' => true])
            );
        }
    }
}
