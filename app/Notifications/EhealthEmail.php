<?php


namespace App\Notifications;

use App\Http\Controllers\SiteBuyProductController;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RenewalsEmail;
use Illuminate\Support\Facades\DB;


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
            return (new MailMessage)
                ->bcc($cc)
                ->subject('CARTA VIRTUALHOSPITAL.BLUE')
                ->view('ehealth', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'code' => $this->code
                    ])
                ->from([$cc => 'Golinucci SRL']);;
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
