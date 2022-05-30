<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class FinalEmailWithAttachments extends Notification
{
    use Queueable;

    protected $cart;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cart)
    {
        $this->cart = $cart;
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
        $user = $this->cart->user;
        $storage_path = Storage::disk('private')->getAdapter()->getPathPrefix();
        $certificate_folder = 'certificates/user_' . $user->id . '/cart_' . $this->cart->id;
        $file_path = $storage_path . $certificate_folder;

        return (new MailMessage)
            ->subject('Q&B Golinucci - Polizza e documenti')
            ->greeting("Salve $user->name")
            ->line('Ecco in allegato la tua polizza e tutta la documentazione allegata per il prodotto che hai acquistato.')
            //->action('Notification Action', url('/'))
            ->salutation('Grazie per aver scelto i prodotti Golinucci!')
            ->attach($file_path . '/certificate-final.pdf')
            ->attach($file_path . '/declarations.pdf')
            ->attach($file_path . '/payment-certificate.pdf');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
