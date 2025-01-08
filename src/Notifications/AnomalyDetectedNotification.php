<?php

namespace AlvinCoded\MtnMomoAi\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AnomalyDetectedNotification extends Notification
{
    use Queueable;

    protected $anomaly;

    public function __construct($anomaly)
    {
        $this->anomaly = $anomaly;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->error()
            ->subject('MTN MOMO Transaction Anomaly Detected')
            ->greeting('Warning: Transaction Anomaly Detected!')
            ->line('An anomaly has been detected in your MTN MOMO transactions.')
            ->line('Anomaly Details:')
            ->line('Type: ' . ($this->anomaly['type'] ?? 'Unknown'))
            ->line('Transaction ID: ' . ($this->anomaly['transaction_id'] ?? 'N/A'))
            ->line('Amount: ' . ($this->anomaly['amount'] ?? 'N/A'))
            ->line('Detection Time: ' . now()->toDateTimeString())
            ->action('View Transaction Details', url('/'))
            ->line('Please review this transaction immediately for potential security concerns.');
    }

    public function toArray($notifiable)
    {
        return [
            'anomaly' => $this->anomaly
        ];
    }
}
