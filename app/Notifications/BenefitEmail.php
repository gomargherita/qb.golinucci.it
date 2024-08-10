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


class BenefitEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $id;
    protected $code;
    protected $name;
    protected $surname;
    protected $fiscal_code;
    protected $via;
    protected $citta;
    protected $cap;
    protected $provincia;
    
    


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
        $this->fiscal_code = $buyer->fiscalcode;
        $this->via = $buyer->via;
        $this->citta = $buyer->citta;
        $this->cap = $buyer->cap;
        $this->provincia = $buyer->provincia;

        
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
        $storage_path = Storage::disk('private')->getAdapter()->getPathPrefix();
        $file_path = $storage_path . "DEM_BENEFIT_golinucci.pdf.pdf";
        
        $data = [
            'name' => $this->name,
            'surname' => $this->surname,
            'code' => $this->code
        ];
            $cc = "info@golinucci.it";
            return (new MailMessage)
                ->bcc($cc)
                ->subject('Sottoscrizione Benefit')
                ->view('benefit', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'code' => $this->code,
                    'fiscal_code' => $this->fiscalcode,
                    'via' => $this->via,
                    'citta' => $this->citta,
                    'cap' => $this->cap,
                    'provincia' => $this->provincia,
                    ])
                ->from([$cc => 'Golinucci SRL'])
                ->attach($file_path);

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
