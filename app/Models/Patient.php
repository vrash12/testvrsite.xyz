<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    // If your table name isn’t the plural of the class name, set it explicitly:
    protected $table = 'patients';

    // If your primary key column isn’t "id", set it explicitly:
    protected $primaryKey = 'patient_id';

    // Disable auto-incrementing if your PK is not an integer autoincrement:
    // public $incrementing = false;
    // protected $keyType = 'string';

    // If you want Eloquent to maintain created_at / updated_at:
    public $timestamps = true;

    // Which columns are mass-assignable
    protected $fillable = [
        'patient_first_name',
        'patient_last_name',
        'patient_birthday',
        'email',
        'phone_number',
        'profile_picture',
        'civil_status',
        'address',
        'city',
    ];

    // Cast columns to appropriate PHP types
    protected $casts = [
        'patient_birthday' => 'date',
    ];

    /**
     * One-to-one: a patient has one set of medical details.
     */
    public function medicalDetails()
    {
        return $this->hasOne(MedicalDetail::class, 'patient_id', 'patient_id');
    }

    /**
     * One-to-one: a patient has one admission record.
     */
    public function admissionDetails()
    {
        return $this->hasOne(AdmissionDetail::class, 'patient_id', 'patient_id');
    }

    /**
     * One-to-one: a patient has one billing information record.
     */
    public function billingInformation()
    {
        return $this->hasOne(BillingInformation::class, 'patient_id', 'patient_id');
    }

    public function dashboard()
    {
        // Get the authenticated patient
        $patient = Auth::user();

        // Fetch prescriptions for the patient
        $prescriptions = Prescription::where('patient_id', $patient->id)->get();

        // Fetch doctor's schedule for the patient
        $schedules = Schedule::where('patient_id', $patient->id)->get();

        // Fetch assigned doctors for the patient
        $assignedDoctors = DoctorAssignment::where('patient_id', $patient->id)->get();

        return view('patient.dashboard', compact('patient', 'prescriptions', 'schedules', 'assignedDoctors'));
    }
}
