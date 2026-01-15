<?php

namespace Webkul\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Order\Contracts\OrderStatusHistory as OrderStatusHistoryContract;
use Webkul\User\Models\UserProxy;

class OrderStatusHistory extends Model implements OrderStatusHistoryContract
{
    protected $table = 'order_status_history';

    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'changed_by',
        'changed_at',
    ];

    /**
     * Get the order that owns the status history.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the user who changed the status.
     */
    public function changedBy()
    {
        return $this->belongsTo(UserProxy::modelClass(), 'changed_by');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            if (empty($history->changed_at)) {
                $history->changed_at = now();
            }

            if (empty($history->changed_by)) {
                $history->changed_by = auth()->id();
            }
        });
    }
}
