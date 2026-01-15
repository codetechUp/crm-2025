<?php

namespace Webkul\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Order\Contracts\Order as OrderContract;
use Webkul\User\Models\UserProxy;

class Order extends Model implements OrderContract
{
    protected $table = 'orders';

    protected $casts = [
        'has_production'         => 'boolean',
        'expected_delivery_date' => 'date',
        'actual_delivery_date'   => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'subject',
        'description',
        'has_production',
        'status',
        'expected_delivery_date',
        'actual_delivery_date',
        'sub_total',
        'discount_percent',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'notes',
        'person_id',
        'user_id',
    ];

    /**
     * Statuts pour commandes avec production
     */
    const STATUS_PRODUCTION = [
        'received'   => 'Commande reçue',
        'preparing'  => 'En cours de préparation',
        'production' => 'En cours de production',
        'finishing'  => 'En phase finition',
        'delivered'  => 'Livrée',
    ];

    /**
     * Statuts pour commandes sans production
     */
    const STATUS_NO_PRODUCTION = [
        'registered' => 'Commande enregistrée',
        'processing' => 'En cours de traitement',
        'executed'   => 'Exécutée',
        'delivered'  => 'Livrée',
    ];

    /**
     * Get available statuses based on production type
     */
    public function getAvailableStatuses()
    {
        return $this->has_production 
            ? self::STATUS_PRODUCTION 
            : self::STATUS_NO_PRODUCTION;
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

    /**
     * Get the status history for the order.
     */
    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistoryProxy::modelClass())
            ->orderBy('changed_at', 'desc');
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Get the person (client) that owns the order.
     */
    public function person()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    /**
     * Check if order is delayed
     */
    public function isDelayed()
    {
        if (!$this->expected_delivery_date) {
            return false;
        }

        // Si déjà livrée, pas de retard
        if ($this->status === 'delivered') {
            return false;
        }

        return now()->greaterThan($this->expected_delivery_date);
    }

    /**
     * Check if order is at risk of being delayed (3 days before expected delivery)
     */
    public function isAtRisk()
    {
        if (!$this->expected_delivery_date) {
            return false;
        }

        // Si déjà livrée, pas de risque
        if ($this->status === 'delivered') {
            return false;
        }

        $daysUntilDelivery = now()->diffInDays($this->expected_delivery_date, false);
        
        return $daysUntilDelivery >= 0 && $daysUntilDelivery <= 3;
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $statuses = $this->getAvailableStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement le numéro de commande
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'CMD-' . date('Y') . '-' . str_pad(
                    self::whereYear('created_at', date('Y'))->count() + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );
            }

            // Définir le statut initial basé sur le type
            if (empty($order->status)) {
                $order->status = $order->has_production ? 'received' : 'registered';
            }
        });
    }
}
