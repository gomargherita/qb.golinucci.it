<?php


namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class EhealthEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $id;
    protected $code;
    protected $name;
    protected $surname;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($buyer)
    {
        $this->id = $buyer->id;
        $this->code = $buyer->ehealth_code;
        $this->name = $buyer->name;
        $this->surname = $buyer->surname;
        
        //error_log($cart->cart->product);die;
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
        $data = [
            'name' => $this->name,
            'surname' => $this->surname,
            'code' => $this->code
        ];
            $cc = "info@golinucci.it";
            $mail = (new MailMessage)
                ->bcc($cc)
                ->from([$cc => 'Golinucci SRL']);
            $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'ehealth', [
                'name' => $this->name,
                'surname' => $this->surname,
                'code' => $this->code,
            ]);
            if ($applied) {
                return $applied;
            }

            return $mail
                ->subject('CARTA VIRTUALHOSPITAL.BLUE')
                ->view('ehealth', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'code' => $this->code,
                ]);
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
