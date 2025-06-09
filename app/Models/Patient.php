<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    // If your table name isn’t the plural of the class name, set it explicitly:
    protected $table = 'patients';

    use Notifiable;

    // Tell Eloquent the primary key is 'patient_id'
    protected $primaryKey = 'patient_id';

    // If you don’t have created_at/updated_at, disable timestamps.
    public $timestamps = true; 
    // (You already have created_at and updated_at, so leave true.)

    // Cast primary key to int:
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'patient_first_name',
        'patient_last_name',
        'patient_birthday',
        'civil_status',
        'email',
        'phone_number',
        'address',
        'password',
        // if you add city / zip to table, include them here
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
