<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $table      = 'bills';
    protected $primaryKey = 'billing_id';
    public $timestamps    = false;   
    protected $casts = [
    'billing_date' => 'date',
];

    // allow mass assignment
    protected $guarded = [];

    /** A bill belongs to a patient */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /** A bill has many items */
    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class, 'billing_id');
    }
}
