<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    
    protected $table = 'companies';
    const UPDATED_AT = null;

    protected $fillable = [
        'name', 'contact_info', 'created_at',
    ];

    protected $casts = [
        'contact_info' => 'array', // JSONB casté en array
        'created_at' => 'datetime',
    ];

    // Relations
    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'company_id', 'uuid');
    }

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'company_id', 'uuid');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'company_id', 'uuid');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'uuid');
    }
}