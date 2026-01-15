<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;

class StockEntryItem extends Model
{
    protected $fillable = [
        'stock_entry_id',
        'product_id',
        'quantity',
    ];

    public function stock_entry()
    {
        return $this->belongsTo(StockEntry::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
