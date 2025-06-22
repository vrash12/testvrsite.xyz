<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicalDetail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;  


class Patient extends Authenticatable
{
    use HasFactory;

    protected $table = 'patients';
    protected $guarded    = [];    

    use Notifiable;

    protected $primaryKey = 'patient_id';


    public $timestamps = true; 

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
       
    ];

 
    protected $casts = [
        'patient_birthday' => 'date',
    ];

  public function medicalDetail(): HasOne
    {
        return $this->hasOne(MedicalDetail::class, 'patient_id');
    }


    public function admissionDetail(): HasOne
    {
        return $this->hasOne(AdmissionDetail::class, 'patient_id');
    }
public function setPasswordAttribute($plain)
{
    $this->attributes['password'] = Hash::make($plain);
}
    /**
     * One-to-one: a patient has one billing information record.
     */
    public function billingInformation(): HasOne
    {
        return $this->hasOne(BillingInformation::class, 'patient_id');
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

    public function bills()
    {
        return $this->hasMany(Bill::class,'patient_id');
    }
}
