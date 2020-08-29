<?php

namespace App\Notifications;

use App\Domain\Concierto\Concierto;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InfoToPromotor extends Notification
{
    use Queueable;

    /** @var Concierto  */
    protected $concierto;

    /**
     * Create a new notification instance.
     *
     * @param Concierto $concierto
     */
    public function __construct(Concierto $concierto)
    {
        $this->concierto = $concierto;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('email.info_to_promotor.subject', [
                'nombre' => $notifiable->nombre,
            ]))
            ->line(__($this->concierto->rentabilidad >= 0 ? 'email.info_to_promotor.beneficios' : 'email.info_to_promotor.perdidas', [
                'nombre'       => $this->concierto->nombre,
                'rentabilidad' => abs($this->concierto->rentabilidad),
            ]));
    }
}
