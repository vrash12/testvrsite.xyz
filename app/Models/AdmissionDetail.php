<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDetail extends Model
{
    protected $table      = 'admission_details';
    protected $primaryKey = 'admission_id';
    public $timestamps    = true;
    protected $guarded    = [];

    // Tell Eloquent to cast admission_date to a Carbon instance
    protected $casts = [
        'admission_date' => 'date',
        // if you have created_at/updated_at columns, cast them too:
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
