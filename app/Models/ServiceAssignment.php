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

    /**
     * Doctor who placed the order
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id', 'user_id');
    }

    /**
     * Patient who the order belongs to
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    /**
     * The service/medication assigned
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(HospitalService::class, 'service_id', 'service_id');
    }
}
