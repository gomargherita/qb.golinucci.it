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



class SaluteSportEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $surname;
    protected $total_payable;
    protected $causale;
    protected $profession;
    protected $fiscalcode;
    protected $dob;
    protected $phone;
    protected $via;
    protected $citta;
    protected $cap;
    protected $provincia;
    protected $morte_da_infortuni;
    protected $invalidita_permanente_infortuni;
    protected $rimborso_infortuni;
    protected $franchigia1;
    protected $franchigia2;
    protected $filepath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($buyer, $morte_da_infortuni, $invalidita_permanente_infortuni, $rimborso_infortuni, $franchigia1, $franchigia2, $filepath)
    {
        
        $this->name = $buyer->name;
        $this->surname = $buyer->surname;
        $this->total_payable = $buyer->total_payable;
        $this->causale = $buyer->causale;
        $this->profession = $buyer->profession;
        $this->fiscalcode = $buyer->fiscalcode;
        $this->dob = $buyer->dob;
        $this->phone = $buyer->phone;
        $this->via = $buyer->via;
        $this->citta = $buyer->citta;
        $this->cap = $buyer->cap;
        $this->provincia = $buyer->provincia;
        $this->morte_da_infortuni = $morte_da_infortuni;
        $this->invalidita_permanente_infortuni = $invalidita_permanente_infortuni;
        $this->rimborso_infortuni = $rimborso_infortuni;   
        $this->franchigia1 = $franchigia1;
        $this->franchigia2 = $franchigia2;     
        $this->filepath = $filepath;
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
        $file_path = $storage_path . "pdfs/" . $this->filepath . ".pdf";

        $data = [
            'name' => $this->name,
            'surname' => $this->surname,
        ];
            $cc = "info@golinucci.it";
            return (new MailMessage)
                ->bcc("paolo@golinucci.it")
                ->subject('Conferma adesione programma assicurativo + Sport&Salute')
                ->view('salutesport', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'total_payable' => $this->total_payable,
                    'causale' => $this->causale,
                    'profession' => $this->profession,
                    'fiscalcode' => $this->fiscalcode,
                    'dob' => $this->dob,
                    'phone' => $this->phone,
                    'via' => $this->via,
                    'citta' => $this->citta,
                    'cap' => $this->cap,
                    'provincia' => $this->provincia,
                    'morte_da_infortuni' => $this->morte_da_infortuni,
                    'invalidita_permanente_infortuni' => $this->invalidita_permanente_infortuni,
                    'rimborso_infortuni' => $this->rimborso_infortuni,
                    'franchigia1' => $this->franchigia1,
                    'franchigia2' => $this->franchigia2
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
