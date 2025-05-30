<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalDetail extends Model
{
    use HasFactory;

    protected $table = 'medical_details';

    protected $fillable = [
        'patient_id',
        'primary_reason',
        'temperature',
        'blood_pressure',
        'weight',
        'height',
        'heart_rate',
        'medical_history',        // stored as JSON
        'other_medical_history',
        'allergies',              // stored as JSON
        'other_allergies',
    ];

    protected $casts = [
        'medical_history' => 'array',
        'allergies'       => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
}
