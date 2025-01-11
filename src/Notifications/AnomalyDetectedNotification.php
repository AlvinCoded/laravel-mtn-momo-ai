<?php

namespace AlvinCoded\MtnMomoAi\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class AnomalyDetectedNotification
 * 
 * Handles notifications for detected anomalies in MTN MOMO transactions.
 * This notification is triggered when the AI system detects unusual patterns
 * or potential security concerns in transaction data.
 *
 * @package AlvinCoded\MtnMomoAi\Notifications
 * @author Alvin Panford <panfordalvin@gmail.com>
 * @since 1.0.0
 */
class AnomalyDetectedNotification extends Notification
{
    use Queueable;

    /**
     * The anomaly data containing transaction details and detection information.
     *
     * @var array
     */
    protected $anomaly;

    /**
     * Create a new notification instance.
     *
     * @param array $anomaly An array containing anomaly details with the following structure:
     *                      [
     *                          'type' => string,           // Type of anomaly detected
     *                          'transaction_id' => string, // ID of the suspicious transaction
     *                          'amount' => float|string,   // Transaction amount
     *                          'severity' => string,       // Severity level of the anomaly
     *                          'details' => array,         // Additional detection details
     *                          'timestamp' => string       // When the anomaly was detected
     *                      ]
     * @return void
     */
    public function __construct($anomaly)
    {
        $this->anomaly = $anomaly;
    }

    /**
     * Get the notification delivery channels.
     *
     * @param mixed $notifiable The entity receiving the notification
     * @return array Returns an array of channels through which the notification should be delivered
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * 
     * Builds an email message containing detailed information about the detected anomaly,
     * including transaction details and recommended actions.
     *
     * @param mixed $notifiable The entity receiving the notification
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
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

    /**
     * Get the array representation of the notification.
     * 
     * Provides a serializable representation of the anomaly notification
     * for storage or transmission through other channels.
     *
     * @param mixed $notifiable The entity receiving the notification
     * @return array Returns an array containing the complete anomaly data
     */
    public function toArray($notifiable)
    {
        return [
            'anomaly' => $this->anomaly
        ];
    }
}
