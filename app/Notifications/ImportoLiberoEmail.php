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
use Illuminate\Support\Facades\Storage;



class ImportoLiberoEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $surname;
    protected $total_payable;
    protected $causale;
errore
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($buyer)
    {
        
        $this->name = $buyer->name;
        $this->surname = $buyer->surname;
        $this->total_payable = $buyer->total_payable;
        $this->causale = $buyer->causale;
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
        ];
            $cc = "info@golinucci.it";
            return (new MailMessage)
                ->bcc("paolo@golinucci.it")
                ->subject('Conferma acquisto')
                ->view('importolibero', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'total_payable' => $this->total_payable,
                    'causale' => $this->causale,

                    ])
                ->from([$cc => 'Golinucci SRL']);
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
