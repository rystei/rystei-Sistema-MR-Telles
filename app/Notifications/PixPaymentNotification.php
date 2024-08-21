<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PixPaymentNotification extends Notification
{
    use Queueable;

    protected $paymentDetails;

    public function __construct($paymentDetails)
    {
        $this->paymentDetails = $paymentDetails;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Confirmação de Pagamento Pix')
                    ->greeting('Olá,')
                    ->line('Seu pagamento via Pix foi concluído com sucesso.')
                    ->line('Detalhes do Pagamento:')
                    ->line('Valor: R$ ' . number_format($this->paymentDetails['amount'], 2, ',', '.'))
                    ->line('Data: ' . $this->paymentDetails['date'])
                    ->line('Obrigado por utilizar nosso serviço!')
                    ->salutation('Atenciosamente, Equipe MR TELLES');
    }
}
