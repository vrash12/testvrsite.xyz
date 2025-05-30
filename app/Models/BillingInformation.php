<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingInformation extends Model
{
    use HasFactory;

    protected $table = 'billing_information'; 
    // if your table is named billing_informations, adjust accordingly

    protected $fillable = [
        'patient_id',
        'payment_method_id',
        'insurance_provider_id',
        'policy_number',
        'group_number',
        'billing_contact_name',
        'billing_contact_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip',
        'billing_notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_method_id');
    }

    public function insuranceProvider()
    {
        return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id', 'insurance_provider_id');
    }
}
