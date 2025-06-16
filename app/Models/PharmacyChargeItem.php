<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PharmacyChargeItem extends Model
{
    protected $table      = 'pharmacy_charge_items';
    protected $primaryKey = 'id';
    public    $timestamps = false;
    protected $guarded    = [];

    public function charge(): BelongsTo
    {
        return $this->belongsTo(PharmacyCharge::class, 'charge_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(HospitalService::class, 'service_id');
    }

    public function getMedicationNameAttribute(): string
    {
        return $this->service?->department?->department_name ?? '—';
    }
}
