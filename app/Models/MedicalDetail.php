<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalDetail extends Model
{
    protected $table      = 'medical_details';
    protected $primaryKey = 'medical_id';
    public $timestamps    = true;
    protected $guarded    = [];    // all fields mass-assignable

   protected $casts = [
    'medical_history'   => 'array',
    'allergies'         => 'array',
    'created_at'        => 'datetime',
    'updated_at'        => 'datetime',
];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
