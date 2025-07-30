<?php
// app/Models/Dispute.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;   // ← add this
use App\Models\BillItem;
use App\Models\Patient;

class Dispute extends Model
{
    protected $primaryKey = 'dispute_id';
    public $timestamps    = false;

    protected $fillable = [
        'billing_item_id',
        'patient_id',
        'datetime',
        'reason',
        'status',
        'approved_by',
    ];

    public function billItem(): BelongsTo
    {
        return $this->belongsTo(
            BillItem::class,
            'billing_item_id',
            'billing_item_id'
        );
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class,
            'patient_id',
            'patient_id'
        );
    }
}
