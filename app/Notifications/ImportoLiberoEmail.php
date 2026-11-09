<?php


namespace App\Notifications;

use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;



class ImportoLiberoEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $surname;
    protected $total_payable;
    protected $causale;

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
            $mail = (new MailMessage)
                ->bcc("paolo@golinucci.it")
                ->from([$cc => 'Golinucci SRL']);
            $applied = app(EmailTemplateService::class)->applyIfPresent($mail, 'importo_libero', [
                'name' => $this->name,
                'surname' => $this->surname,
                'amount' => $this->total_payable,
                'causale' => $this->causale,
            ]);
            if ($applied) {
                return $applied;
            }

            return $mail
                ->subject('Conferma acquisto')
                ->view('importolibero', [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'total_payable' => $this->total_payable,
                    'causale' => $this->causale,
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
