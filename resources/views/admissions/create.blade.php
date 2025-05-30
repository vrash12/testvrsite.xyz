{{-- resources/views/admissions/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex">
  {{-- Sidebar auto from layouts --}}
  <div class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-6">New Patient Admission</h1>

    <div x-data="{ 
    step: 1,
    completedSteps: {
        1: false,
        2: false,
        3: false,
        4: false
    },
    markStepAsCompleted(stepNumber) {
        this.completedSteps[stepNumber] = true;
    },
    isStepComplete(stepNumber) {
        if (stepNumber === 1) {
            return this.completedSteps[1] || (
                $refs.firstName.value && 
                $refs.lastName.value
            );
        }
        if (stepNumber === 2) {
            return this.completedSteps[2] || (
                $refs.primaryReason.value
            );
        }
        if (stepNumber === 3) {
            return this.completedSteps[3] || (
                $refs.admissionDate.value &&
                $refs.admissionType.value &&
                $refs.department.value &&
                $refs.attendingDoctor.value
            );
        }
        if (stepNumber === 4) {
            return this.completedSteps[4] || (
                $refs.paymentMethod.value
            );
        }
        return false;
    }
}" class="bg-white rounded-lg shadow p-6">
      {{-- Tab Buttons for Step Navigation --}}
      {{-- Tab Buttons for Step Navigation --}}
<div class="flex space-x-2 mb-6">
<button
    type="button"
    :class="{ 
        'bg-blue-600 text-white': step === 1 || completedSteps[1],
        'bg-gray-100 text-gray-700': step !== 1 && !completedSteps[1],
        'border-2 border-green-500': completedSteps[1]
    }"
    class="px-4 py-2 rounded-md transition flex items-center space-x-2"
    @click="step = 1"
>
    <span>Personal Information</span>
    <svg x-show="completedSteps[1]" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
</button>

<!-- Add this right after your tab buttons -->
<div class="w-full bg-gray-200 rounded-full h-2 mb-6">
    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
         :style="'width: ' + (Object.values(completedSteps).filter(Boolean).length * 25) + '%'">
    </div>
</div>
<button
    type="button"
    :class="{ 
        'bg-blue-600 text-white': step === 2 || completedSteps[2],
        'bg-gray-100 text-gray-700': step !== 2 && !completedSteps[2],
        'border-2 border-green-500': completedSteps[2]
    }"
    class="px-4 py-2 rounded-md transition flex items-center space-x-2"
    @click="step = 2"
>
    <span>Medical Details</span>
    <svg x-show="completedSteps[2]" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
</button>
<!-- Add this right after your tab buttons -->
<div class="w-full bg-gray-200 rounded-full h-2 mb-6">
    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
         :style="'width: ' + (Object.values(completedSteps).filter(Boolean).length * 25) + '%'">
    </div>
</div>

{{-- Admission Details --}}
    <button
        type="button"
        :class="{ 
            'bg-blue-600 text-white': step === 3 || completedSteps[3],
            'bg-gray-100 text-gray-700': step !== 3 && !completedSteps[3],
            'border-2 border-green-500': completedSteps[3]
        }"
        class="px-4 py-2 rounded-md transition flex items-center space-x-2"
        @click="step = 3"
    >
        <span>Admission Details</span>
        <svg x-show="completedSteps[3]" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </button>
    <!-- Add this right after your tab buttons -->
<div class="w-full bg-gray-200 rounded-full h-2 mb-6">
    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
         :style="'width: ' + (Object.values(completedSteps).filter(Boolean).length * 25) + '%'">
    </div>
</div>

    <button
        type="button"
        :class="{ 
            'bg-blue-600 text-white': step === 4 || completedSteps[4],
            'bg-gray-100 text-gray-700': step !== 4 && !completedSteps[4],
            'border-2 border-green-500': completedSteps[4]
        }"
        class="px-4 py-2 rounded-md transition flex items-center space-x-2"
        @click="step = 4"
    >
        <span>Billing Details</span>
        <svg x-show="completedSteps[4]" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </button>
<!-- Add this right after your tab buttons -->
<div class="w-full bg-gray-200 rounded-full h-2 mb-6">
    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
         :style="'width: ' + (Object.values(completedSteps).filter(Boolean).length * 25) + '%'">
    </div>
</div>

</div>
      <form action="{{ route('admin.store-patient') }}" method="POST">
        @csrf

        {{-- Step 1: Personal Information --}}
        <div x-show="step === 1" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">First Name</label>
              <input 
    type="text" 
    name="first_name" 
    x-ref="firstName"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
    required
>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Last Name</label>
              <input type="text" name="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Birthday</label>
              <input type="date" name="birthday" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Civil Status</label>
              <input type="text" name="civil_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Phone Number (+63)</label>
              <input type="tel" name="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700">Address</label>
              <input type="text" name="address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">City</label>
              <input type="text" name="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>
        </div>

        {{-- Step 2: Medical Details --}}
        <div x-show="step === 2" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Primary Reason for Admission</label>
            <input 
    type="text" 
    name="primary_reason" 
    x-ref="primaryReason"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
    required
>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Temperature (°F)</label>
              <input type="text" name="temperature" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Blood Pressure</label>
              <input type="text" name="blood_pressure" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Weight (KG)</label>
              <input type="text" name="weight" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Height (cm)</label>
              <input type="text" name="height" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Heart Rate (BPM)</label>
              <input type="text" name="heart_rate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Medical History</label>
            <div class="space-y-2">
              <label class="inline-flex items-center">
                <input type="checkbox" name="medical_history[]" value="Hyper Tension" class="form-checkbox">
                <span class="ml-2">Hyper Tension</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="medical_history[]" value="Diabetes" class="form-checkbox">
                <span class="ml-2">Diabetes</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="medical_history[]" value="Heart Disease" class="form-checkbox">
                <span class="ml-2">Heart Disease</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="medical_history[]" value="COPD" class="form-checkbox">
                <span class="ml-2">COPD</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="medical_history[]" value="Others" class="form-checkbox">
                <span class="ml-2">Others (please specify)</span>
              </label>
              <input type="text" name="other_medical_history" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Allergies</label>
            <div class="space-y-2">
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="Penicillin" class="form-checkbox">
                <span class="ml-2">Penicillin</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="Sulfa Drugs" class="form-checkbox">
                <span class="ml-2">Sulfa Drugs</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="NSAIDs" class="form-checkbox">
                <span class="ml-2">NSAIDs</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="Latex" class="form-checkbox">
                <span class="ml-2">Latex</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="Contrast Dye" class="form-checkbox">
                <span class="ml-2">Contrast Dye</span>
              </label>
              <label class="inline-flex items-center">
                <input type="checkbox" name="allergies[]" value="No Known Allergies" class="form-checkbox">
                <span class="ml-2">No Known Allergies</span>
              </label>
              <input type="text" name="other_allergies" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Others (please specify)">
            </div>
          </div>
        </div>

        {{-- Step 3: Admission Details --}}
<div x-show="step === 3" class="space-y-6">
    <div>
        <h2 class="text-lg font-medium text-gray-900 mb-2">Admission Details</h2>
        <p class="text-sm text-gray-500 mb-6">Specify admission type, department, and assign a doctor</p>
    </div>

    <!-- First Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Admission Date</label>
            <input 
    type="date" 
    name="admission_date" 
    x-ref="admissionDate"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
    required
>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Admission Type</label>
            <select 
                name="admission_type" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Select Type</option>
                <option value="emergency">Emergency</option>
                <option value="regular">Regular</option>
                <option value="transfer">Transfer</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Admission Source</label>
            <select 
                name="admission_source" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Select Source</option>
                <option value="emergency">Emergency Room</option>
                <option value="opd">OPD</option>
                <option value="transfer">Transfer</option>
                <option value="referral">Referral</option>
            </select>
        </div>
    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <select 
                name="department" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Select Department</option>
                <option value="internal_medicine">Internal Medicine</option>
                <option value="surgery">Surgery</option>
                <option value="pediatrics">Pediatrics</option>
                <option value="obstetrics">Obstetrics</option>
                <option value="cardiology">Cardiology</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Attending Doctor/s</label>
            <select 
                name="attending_doctor" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Select Doctor</option>
                <option value="dr_smith">Dr. Smith</option>
                <option value="dr_jones">Dr. Jones</option>
                <option value="dr_wilson">Dr. Wilson</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Room Number</label>
            <input 
                type="text" 
                name="room_number" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., 301"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Bed Number</label>
            <input 
                type="text" 
                name="bed_number" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., A"
            >
        </div>
    </div>

    <!-- Admission Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Admission Notes</label>
        <textarea 
            name="admission_notes" 
            rows="4" 
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter any additional notes about the admission..."
        ></textarea>
    </div>
</div>

{{-- Step 4: Billing Details --}}
<div x-show="step === 4" class="space-y-6">
    <div>
        <h2 class="text-lg font-medium text-gray-900 mb-2">Billing Details</h2>
        <p class="text-sm text-gray-500 mb-6">Enter information about payment and billing preferences</p>
    </div>

    <!-- Payment Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
            <select 
    name="payment_method" 
    x-ref="paymentMethod"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
    required
>
                <option value="">Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="insurance">Insurance</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Insurance Provider</label>
            <select 
                name="insurance_provider" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Select Insurance Provider</option>
                <option value="blue_cross">Blue Cross</option>
                <option value="medicare">Medicare</option>
                <option value="medicaid">Medicaid</option>
                <option value="aetna">Aetna</option>
                <option value="cigna">Cigna</option>
            </select>
        </div>
    </div>

    <!-- Insurance Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Policy Number</label>
            <input 
                type="text" 
                name="policy_number" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter policy number"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Group Number</label>
            <input 
                type="text" 
                name="group_number" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter group number"
            >
        </div>
    </div>

    <!-- Billing Contact -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700">Billing Contact Name</label>
            <input 
                type="text" 
                name="billing_contact_name" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter billing contact name"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Billing Contact Phone</label>
            <input 
                type="tel" 
                name="billing_contact_phone" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="+63 XXX XXX XXXX"
            >
        </div>
    </div>

    <!-- Billing Address -->
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Billing Address</label>
            <input 
                type="text" 
                name="billing_address" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter street address"
            >
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">City</label>
                <input 
                    type="text" 
                    name="billing_city" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">State/Province</label>
                <input 
                    type="text" 
                    name="billing_state" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">ZIP/Postal Code</label>
                <input 
                    type="text" 
                    name="billing_zip" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
        </div>
    </div>

    <!-- Additional Notes -->
    <div>
        <label class="block text-sm font-medium text-gray-700">Billing Notes</label>
        <textarea 
            name="billing_notes" 
            rows="3" 
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter any additional billing notes or special instructions..."
        ></textarea>
    </div>
</div>

{{-- Form Navigation --}}
<div class="flex justify-between mt-8">
    <button
        type="button"
        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        @click="step = step > 1 ? step - 1 : 1"
    >
        Previous
    </button>

    <button
        type="submit"
        class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
        x-show="step === 4"
    >
        FINISH
    </button>

    <button
    type="button"
    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
    @click="markStepAsCompleted(step); step = step + 1"
    x-show="step < 4"
>
    Next
</button>
</div>

      
      </form>
    </div>
  </div>
</div>
@endsection
