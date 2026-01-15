<?php

namespace Webkul\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Webkul\Order\Contracts\Order;

class OrderDelayNotification extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var string (delayed|at_risk)
     */
    protected $type;

    /**
     * Create a new notification instance.
     *
     * @param  Order  $order
     * @param  string  $type
     * @return void
     */
    public function __construct(Order $order, string $type = 'delayed')
    {
        $this->order = $order;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = $this->type === 'delayed' 
            ? '⚠️ Commande en Retard' 
            : '⏰ Commande à Risque de Retard';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour,')
            ->line($this->getMessageLine());

        $message->line('**Détails de la commande:**')
            ->line('Numéro: ' . $this->order->order_number)
            ->line('Sujet: ' . $this->order->subject)
            ->line('Client: ' . $this->order->person->name)
            ->line('Statut: ' . $this->order->getStatusLabel())
            ->line('Date de livraison prévue: ' . $this->order->expected_delivery_date->format('d/m/Y'));

        if ($this->type === 'delayed') {
            $daysLate = now()->diffInDays($this->order->expected_delivery_date);
            $message->line("**Retard: {$daysLate} jour(s)**");
        } else {
            $daysUntil = now()->diffInDays($this->order->expected_delivery_date);
            $message->line("**Livraison dans {$daysUntil} jour(s)**");
        }

        $message->action('Voir la commande', route('admin.orders.show', $this->order->id))
            ->line('Merci de prendre les mesures nécessaires.');

        return $message;
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
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'type'         => $this->type,
            'message'      => $this->getMessageLine(),
        ];
    }

    /**
     * Get the notification message line.
     *
     * @return string
     */
    protected function getMessageLine()
    {
        if ($this->type === 'delayed') {
            return "La commande #{$this->order->order_number} est en retard. La date de livraison prévue était le {$this->order->expected_delivery_date->format('d/m/Y')}.";
        }

        return "La commande #{$this->order->order_number} risque d'être en retard. La livraison est prévue pour le {$this->order->expected_delivery_date->format('d/m/Y')}.";
    }
}
