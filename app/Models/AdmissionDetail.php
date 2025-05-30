<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdmissionDetail extends Model
{
    use HasFactory;

    protected $table = 'admission_details';

    protected $fillable = [
        'patient_id',
        'admission_date',
        'admission_type',
        'admission_source',
        'department_id',
        'doctor_id',
        'room_number',
        'bed_number',
        'admission_notes',
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
