<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';
    const UPDATED_AT = null; 

    protected $fillable = [
        'serial_num', 'company_id', 'code_encrypted', 'type', 'amount', 'status', 'expires_at', 'created_at',
    ];

    protected $casts = [
        'serial_num' => 'string'
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function physicalCard()
    {
        return $this->hasOne(PhysicalCard::class, 'voucher_id', 'id');
    }

    public function redemptions()
    {
        return $this->hasMany(VoucherRedemption::class, 'voucher_id', 'id');
    }
}