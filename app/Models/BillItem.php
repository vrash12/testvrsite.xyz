<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillItem extends Model
{
    protected $table         = 'bill_items';
    protected $primaryKey    = 'billing_item_id';
    public $timestamps       = false;
    protected $guarded       = [];  // all fields mass-assignable

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'billing_id');
    }

    protected $casts = [
    'billing_date' => 'date',
];

    public function prescriptionItem(): BelongsTo
    {
        return $this->belongsTo(PrescriptionItem::class, 'prescription_item_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ServiceAssignment::class, 'assignment_id');
    }
}