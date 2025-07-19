<?php
// app/Models/PharmacyCharge.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PharmacyCharge extends Model
{
    protected $table      = 'pharmacy_charges';
    protected $primaryKey = 'id';
    protected $guarded    = [];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

   public function items()
{
    return $this->hasMany(PharmacyChargeItem::class, 'charge_id');
}

public function getTotalAmountAttribute()
{
    return $this->items->sum('amount');
}
}
