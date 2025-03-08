<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributors';

    protected $fillable = [
        'uuid', 'company_id', 'name', 'contact_info',
    ];

    protected $casts = [
        'contact_info' => 'array', // JSONB casté en array
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'uuid');
    }

    public function physicalCards()
    {
        return $this->hasMany(PhysicalCard::class, 'distributor_id', 'uuid');
    }
}