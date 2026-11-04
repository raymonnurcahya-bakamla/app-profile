<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        $topics = QuestionTopic::all();

        $topic1 = $topics->firstWhere('slug', 'layanan-kependudukan') ?? $topics->first();
        $topic2 = $topics->firstWhere('slug', 'informasi-keterbukaan-publik') ?? $topics->skip(1)->first();

        // 1. Answered Question
        $q1 = Question::firstOrCreate(
            ['subject' => 'Persyaratan Legalisir Dokumen Kependudukan'],
            [
                'topic_id' => $topic1?->id,
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081298765432',
                'body' => 'Selamat siang, saya ingin menanyakan apakah untuk legalisir kartu keluarga bisa dilakukan secara online atau harus datang langsung ke loket kantor dinas?',
                'status' => 'answered',
                'ip_address' => '127.0.0.1',
            ]
        );

        QuestionAnswer::firstOrCreate(
            ['question_id' => $q1->id],
            [
                'user_id' => $admin?->id,
                'body' => '<p>Halo Bapak Budi Santoso,</p><p>Untuk dokumen kependudukan yang sudah memiliki tanda tangan elektronik (QR Code) dari Dukcapil, tidak perlu dilegalisir lagi sesuai dengan Permendagri No. 104 Tahun 2019. Dokumen tersebut sudah sah secara hukum.</p><p>Namun jika dokumen lama yang belum ber-barcode, silakan datang langsung ke loket dengan membawa dokumen asli.</p>',
                'status' => 'published',
                'is_published' => true,
                'answered_at' => now(),
            ]
        );

        // 2. Pending Question
        Question::firstOrCreate(
            ['subject' => 'Permintaan Salinan Data Statistik Tahunan'],
            [
                'topic_id' => $topic2?->id,
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'phone' => '085712345678',
                'body' => 'Apakah publik dapat mengakses buku data agregat statistik publikasi tahun 2025 dalam format PDF atau Excel untuk keperluan penelitian skripsi?',
                'status' => 'pending',
                'ip_address' => '127.0.0.1',
            ]
        );
    }
}
