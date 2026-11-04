<?php

namespace App\Notifications;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionAnsweredNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Question $question,
        public QuestionAnswer $answer
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pertanyaan Anda Telah Dijawab: '.$this->question->subject)
            ->greeting('Halo '.$this->question->name.',')
            ->line('Pertanyaan yang Anda sampaikan dengan subjek "'.$this->question->subject.'" telah dijawab oleh tim kami.')
            ->line('Jawaban:')
            ->line(strip_tags($this->answer->body))
            ->line('Terima kasih atas partisipasi dan aspirasi yang Anda berikan.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'question_id' => $this->question->id,
            'subject' => $this->question->subject,
            'answer_id' => $this->answer->id,
        ];
    }
}
