<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Contact\Models\PersonProxy;
use Webkul\User\Models\UserProxy;

class StockEntry extends Model
{
    protected $fillable = [
        'date_appro',
        'person_id',
        'notes',
        'user_id',
    ];

    public function person()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    public function items()
    {
        return $this->hasMany(StockEntryItem::class);
    }
}
