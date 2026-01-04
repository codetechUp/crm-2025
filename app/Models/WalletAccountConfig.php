<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletAccountConfig extends Model
{
    protected $fillable = [
        'wallet_account_id',
        'iban',
        'account_number',
        'bank_name',
        'wallet_address',
        'blockchain_network',
        'metadata',
        'validated_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'validated_at' => 'datetime'
    ];

    public function account()
    {
        return $this->belongsTo(WalletAccount::class);
    }
}
