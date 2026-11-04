<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_question_topics(): void
    {
        QuestionTopic::factory()->create(['name' => 'Layanan Kependudukan', 'is_active' => true]);
        QuestionTopic::factory()->create(['name' => 'Topik Arsip', 'is_active' => false]);

        $response = $this->getJson(route('api.v1.question-topics.index'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Layanan Kependudukan'])
            ->assertJsonMissing(['name' => 'Topik Arsip']);
    }

    public function test_can_get_public_answered_questions(): void
    {
        $topic = QuestionTopic::factory()->create();
        $user = User::factory()->create();

        // 1. Answered question with published answer
        $q1 = Question::factory()->create([
            'topic_id' => $topic->id,
            'subject' => 'Cara Membuat KTP Baru',
            'status' => 'answered',
        ]);
        QuestionAnswer::create([
            'question_id' => $q1->id,
            'user_id' => $user->id,
            'body' => 'Bawa surat pengantar dari RT/RW setempat.',
            'status' => 'published',
            'is_published' => true,
            'answered_at' => now(),
        ]);

        // 2. Pending question (should not be in public listing)
        Question::factory()->create([
            'topic_id' => $topic->id,
            'subject' => 'Pertanyaan Rahasia Masih Pending',
            'status' => 'pending',
        ]);

        $response = $this->getJson(route('api.v1.questions.index'));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['subject' => 'Cara Membuat KTP Baru'])
            ->assertJsonMissing(['subject' => 'Pertanyaan Rahasia Masih Pending']);
    }

    public function test_can_submit_new_question_with_validation(): void
    {
        $topic = QuestionTopic::factory()->create();

        $payload = [
            'topic_id' => $topic->id,
            'name' => 'Ahmad Dahlan',
            'email' => 'ahmad@example.com',
            'phone' => '08123456789',
            'subject' => 'Pertanyaan Terkait Jadwal Sosialisasi',
            'body' => 'Kapan sosialisasi program digitalisasi di kelurahan kami akan dilaksanakan?',
        ];

        $response = $this->postJson(route('api.v1.questions.store'), $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.subject', 'Pertanyaan Terkait Jadwal Sosialisasi')
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('questions', [
            'topic_id' => $topic->id,
            'email' => 'ahmad@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_submitting_invalid_question_fails_validation(): void
    {
        $response = $this->postJson(route('api.v1.questions.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['topic_id', 'name', 'email', 'subject', 'body']);
    }

    public function test_can_get_faqs_filtered_by_topic(): void
    {
        $topic1 = QuestionTopic::factory()->create(['slug' => 'topik-satu']);
        $topic2 = QuestionTopic::factory()->create(['slug' => 'topik-dua']);

        Faq::factory()->create([
            'topic_id' => $topic1->id,
            'question' => 'Pertanyaan untuk Topik Satu?',
            'is_active' => true,
        ]);

        Faq::factory()->create([
            'topic_id' => $topic2->id,
            'question' => 'Pertanyaan untuk Topik Dua?',
            'is_active' => true,
        ]);

        $response = $this->getJson(route('api.v1.faqs.index', ['topic' => 'topik-satu']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['question' => 'Pertanyaan untuk Topik Satu?'])
            ->assertJsonMissing(['question' => 'Pertanyaan untuk Topik Dua?']);
    }
}
