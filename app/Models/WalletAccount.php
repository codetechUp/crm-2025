<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccount extends Model
{
    protected $fillable = [
        'wallet_id',
        'currency_code',
        'type',
        'balance',
        'is_configured',
        'status'
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'is_configured' => 'boolean'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function config()
    {
        return $this->hasOne(WalletAccountConfig::class);
    }
}
