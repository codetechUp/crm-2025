<?php

namespace Webkul\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Order\Contracts\OrderItem as OrderItemContract;
use Webkul\Product\Models\ProductProxy;

class OrderItem extends Model implements OrderItemContract
{
    protected $table = 'order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'sku',
        'quantity',
        'price',
        'total',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the product associated with the item.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
