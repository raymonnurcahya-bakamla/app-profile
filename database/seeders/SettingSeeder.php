<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General / Profile
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Dinas Komunikasi dan Informatika', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Mewujudkan Pelayanan Informasi Publik yang Transparan dan Akuntabel', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_description', 'value' => 'Portal resmi layanan informasi dan interaksi masyarakat.', 'type' => 'textarea'],
            ['group' => 'general', 'key' => 'site_logo', 'value' => null, 'type' => 'image'],

            // Contact
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'kontak@instansi.go.id', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(021) 1234-5678', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_whatsapp', 'value' => '081234567890', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Merdeka No. 45, Jakarta', 'type' => 'textarea'],

            // Social Media
            ['group' => 'social', 'key' => 'social_facebook', 'value' => 'https://facebook.com/instansi', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => 'https://instagram.com/instansi', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_youtube', 'value' => 'https://youtube.com/@instansi', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_twitter', 'value' => 'https://x.com/instansi', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
