<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDetail extends Model
{
    protected $table      = 'admission_details';
    protected $primaryKey = 'admission_id';
    public    $timestamps = true;
    protected $guarded    = [];

    protected $casts = [
        'admission_date' => 'date',
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

    /**
     * Link admission_details.room_number → rooms.room_number
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_number', 'room_number');
    }

    /**
     * (Optionally) if you also want to fetch the Bed by its number:
     */
    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'bed_number', 'bed_number');
    }
}
