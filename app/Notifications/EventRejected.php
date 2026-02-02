<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Event $event, ?string $reason = null)
    {
        $this->event = $event;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/events/submit');

        $mail = (new MailMessage)
            ->subject('Seu evento não foi aprovado')
            ->greeting('Olá')
            ->line('Lamentamos informar que seu evento "'.$this->event->title.'" não foi aprovado.');

        if ($this->reason) {
            $mail->line('Motivo: '.$this->reason);
        }

        $mail->action('Enviar outro evento', $url)
             ->line('Se precisar de mais informações, responda este e-mail ou contacte o administrador.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'reason' => $this->reason,
        ];
    }
}
