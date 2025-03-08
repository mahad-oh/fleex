<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';

    protected $fillable = [
        'id', 'company_id', 'wallet_id', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function redemptions()
    {
        return $this->hasMany(VoucherRedemption::class, 'wallet_id', 'id');
    }
}