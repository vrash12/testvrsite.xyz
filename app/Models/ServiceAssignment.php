<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\HospitalService;
use App\Models\Patient;
use App\Models\User;

class ServiceAssignment extends Model
{
    protected $table = 'service_assignments';
    protected $primaryKey = 'assignment_id';
    public $timestamps = false; // no created_at/updated_at columns
    protected $guarded = [];
protected $casts = [
    'datetime' => 'datetime',
];

   public function service()  { return $this->belongsTo(HospitalService::class, 'service_id'); }
    public function doctor()   { return $this->belongsTo(Doctor::class,         'doctor_id');  }

    /**
     * Patient who the order belongs to
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

  
}
