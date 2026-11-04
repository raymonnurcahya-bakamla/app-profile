<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topic = QuestionTopic::first();

        $faqs = [
            [
                'question' => 'Bagaimana cara mengajukan pertanyaan melalui aplikasi?',
                'answer' => '<p>Masyarakat dapat membuka menu "Tanya Kami" di aplikasi Flutter, mengisi formulir dengan data identitas dan pertanyaan yang jelas, lalu menekan tombol Kirim. Anda akan mendapatkan notifikasi via email saat pertanyaan selesai dijawab.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama estimasi waktu respon atas pertanyaan masyarakat?',
                'answer' => '<p>Pertanyaan yang masuk akan diverifikasi oleh petugas dalam 1x24 jam kerja, dan jawaban resmi diberikan maksimal dalam 3 hari kerja tergantung kompleksitas materi pertanyaan.</p>',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah layanan informasi dan konsultasi ini dipungut biaya?',
                'answer' => '<p>Seluruh layanan pengajuan pertanyaan dan permohonan informasi publik melalui portal ini adalah <strong>GRATIS</strong> tidak dipungut biaya apapun.</p>',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['topic_id' => $topic?->id])
            );
        }
    }
}
