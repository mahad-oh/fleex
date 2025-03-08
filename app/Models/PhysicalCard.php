<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalCard extends Model
{
    
    protected $table = 'physical_cards';

    protected $fillable = [
         'voucher_id', 'printed_at', 'distributor_id', 'distributed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'distributed_at' => 'datetime',
    ];

    // Relations
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }
}