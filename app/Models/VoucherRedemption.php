<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VoucherRedemption extends Model
{
    
    protected $table = 'voucher_redemptions';

    protected $fillable = [
        'voucher_id', 'wallet_id', 'redeemed_at',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    // Relations
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}