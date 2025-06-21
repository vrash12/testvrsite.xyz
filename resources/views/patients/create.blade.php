{{-- resources/views/patients/create.blade.php --}}
@extends('layouts.admission')

@section('content')
<style>
  /* mark completed tabs with a green check */
  .nav-tabs .nav-link.completed {
    color: #28a745;
  }
  .nav-tabs .nav-link.completed::after {
    content: ' ✓';
    color: #28a745;
    font-weight: bold;
  }
</style>

<div class="container-fluid">
  <div class="py-4">
    <h2 class="text-primary">New Patient Admission</h2>
    <p class="text-muted">Enter patient information and assign to a department and doctor</p>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

   <form method="POST" action="{{ route('admission.patients.store') }}" novalidate>
      @csrf

      {{-- Nav Tabs --}}
      <ul class="nav nav-tabs mb-3" id="patientTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                  type="button" role="tab" aria-controls="personal" aria-selected="true">
            Personal Information
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical"
                  type="button" role="tab" aria-controls="medical" aria-selected="false">
            Medical Details
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="admission-tab" data-bs-toggle="tab" data-bs-target="#admission"
                  type="button" role="tab" aria-controls="admission" aria-selected="false">
            Admission Details
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing"
                  type="button" role="tab" aria-controls="billing" aria-selected="false">
            Billing Details
          </button>
        </li>
      </ul>

      <div class="tab-content" id="patientTabsContent">
        {{-- 1. Personal Information --}}
        <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
          <div class="card mb-4">
            <div class="card-header"><strong>Personal Information</strong></div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" name="patient_first_name"
                         value="{{ old('patient_first_name') }}"
                         class="form-control @error('patient_first_name') is-invalid @enderror"
                         required>
                  @error('patient_first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="patient_last_name"
                         value="{{ old('patient_last_name') }}"
                         class="form-control @error('patient_last_name') is-invalid @enderror"
                         required>
                  @error('patient_last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Birthday</label>
                  <input type="date" name="patient_birthday"
                         value="{{ old('patient_birthday') }}"
                         class="form-control @error('patient_birthday') is-invalid @enderror">
                  @error('patient_birthday')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Civil Status</label>
                  <input type="text" name="civil_status"
                         value="{{ old('civil_status') }}"
                         class="form-control @error('civil_status') is-invalid @enderror"
                         placeholder="e.g. Single, Married">
                  @error('civil_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Phone Number</label>
                  <div class="input-group">
                    <span class="input-group-text">(+63)</span>
                    <input type="text" name="phone_number"
                           value="{{ old('phone_number') }}"
                           class="form-control @error('phone_number') is-invalid @enderror"
                           placeholder="9XXXXXXXXX">
                  </div>
                  @error('phone_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                  <label class="form-label">Address</label>
                  <textarea name="address" rows="2"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Street, Barangay, City, Zip Code">{{ old('address') }}</textarea>
                  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>

              {{-- Info about credentials --}}
              <div class="alert alert-info mt-4">
                <strong>Note:</strong> Email and password will be autogenerated and displayed after saving.
              </div>
            </div>
            <div class="card-footer text-end">
              <button type="button" class="btn btn-primary step-next"
                      data-current="personal" data-next="medical">
                Next
              </button>
            </div>
          </div>
        </div>

        {{-- 2. Medical Details --}}
        <div class="tab-pane fade" id="medical" role="tabpanel" aria-labelledby="medical-tab">
          <div class="card mb-4">
            <div class="card-header">
              <strong>Medical Details</strong>
              <p class="text-muted mb-0">Enter the patient's medical history and current condition</p>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Primary Reason for Admission</label>
                  <input type="text" name="primary_reason" value="{{ old('primary_reason') }}"
                         class="form-control @error('primary_reason') is-invalid @enderror">
                  @error('primary_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                  <label class="form-label">Weight (KG)</label>
                  <input type="number" step="0.1" name="weight" value="{{ old('weight') }}"
                         class="form-control @error('weight') is-invalid @enderror">
                  @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                  <label class="form-label">Height (cm)</label>
                  <input type="number" step="0.1" name="height" value="{{ old('height') }}"
                         class="form-control @error('height') is-invalid @enderror">
                  @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                  <label class="form-label">Temperature (°F)</label>
                  <input type="number" step="0.1" name="temperature" value="{{ old('temperature') }}"
                         class="form-control @error('temperature') is-invalid @enderror">
                  @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                  <label class="form-label">Blood Pressure</label>
                  <input type="text" name="blood_pressure" value="{{ old('blood_pressure') }}"
                         class="form-control @error('blood_pressure') is-invalid @enderror"
                         placeholder="e.g. 120/80">
                  @error('blood_pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                  <label class="form-label">Heart Rate (BPM)</label>
                  <input type="number" name="heart_rate" value="{{ old('heart_rate') }}"
                         class="form-control @error('heart_rate') is-invalid @enderror">
                  @error('heart_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Medical History --}}
                <div class="col-12 mt-3">
                  <label class="form-label">Medical History</label>
                  <div class="row">
                    <div class="col-md-3">
                      @foreach(['hypertension','heart_disease','copd'] as $h)
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                                 name="history_{{ $h }}" id="history_{{ $h }}"
                                 {{ old("history_$h") ? 'checked' : '' }}>
                          <label class="form-check-label" for="history_{{ $h }}">
                            {{ ucwords(str_replace('_',' ',$h)) }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <div class="col-md-3">
                      @foreach(['diabetes','asthma','kidney_disease'] as $h)
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                                 name="history_{{ $h }}" id="history_{{ $h }}"
                                 {{ old("history_$h") ? 'checked' : '' }}>
                          <label class="form-check-label" for="history_{{ $h }}">
                            {{ ucwords(str_replace('_',' ',$h)) }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <div class="col-md-6">
                      <label class="form-label mt-2">Others (please specify)</label>
                      <input type="text" name="history_others" value="{{ old('history_others') }}"
                             class="form-control @error('history_others') is-invalid @enderror"
                             placeholder="Specify if not listed above">
                      @error('history_others')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>

                {{-- Allergies --}}
                <div class="col-12 mt-4">
                  <label class="form-label">Allergies</label>
                  <div class="row">
                    <div class="col-md-3">
                      @foreach(['penicillin','nsaids','contrast_dye'] as $a)
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                                 name="allergy_{{ $a }}" id="allergy_{{ $a }}"
                                 {{ old("allergy_$a") ? 'checked' : '' }}>
                          <label class="form-check-label" for="allergy_{{ $a }}">
                            {{ ucwords(str_replace('_',' ',$a)) }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <div class="col-md-3">
                      @foreach(['sulfa','latex','none'] as $a)
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox"
                                 name="allergy_{{ $a }}" id="allergy_{{ $a }}"
                                 {{ old("allergy_$a") ? 'checked' : '' }}>
                          <label class="form-check-label" for="allergy_{{ $a }}">
                            {{ $a==='none' ? 'No Known Allergies' : ucwords($a) }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                    <div class="col-md-6">
                      <label class="form-label mt-2">Others (please specify)</label>
                      <input type="text" name="allergy_others" value="{{ old('allergy_others') }}"
                             class="form-control @error('allergy_others') is-invalid @enderror"
                             placeholder="Specify if not listed above">
                      @error('allergy_others')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <button type="button" class="btn btn-secondary step-prev" data-current="medical" data-prev="personal">
                Previous
              </button>
              <button type="button" class="btn btn-primary step-next" data-current="medical" data-next="admission">
                Next
              </button>
            </div>
          </div>
        </div>

        {{-- 3. Admission Details --}}
        <div class="tab-pane fade" id="admission" role="tabpanel" aria-labelledby="admission-tab">
          <div class="card mb-4">
            <div class="card-header">
              <strong>Admission Details</strong>
              <p class="text-muted mb-0">Specify admission type, department, and assign a doctor</p>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Admission Date <span class="text-danger">*</span></label>
                  <input type="date" name="admission_date" value="{{ old('admission_date') }}"
                         class="form-control @error('admission_date') is-invalid @enderror" required>
                  @error('admission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Admission Type <span class="text-danger">*</span></label>
                  <input type="text" name="admission_type" value="{{ old('admission_type') }}"
                         class="form-control @error('admission_type') is-invalid @enderror"
                         placeholder="e.g. Inpatient, Outpatient" required>
                  @error('admission_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Admission Source</label>
                  <input type="text" name="admission_source" value="{{ old('admission_source') }}"
                         class="form-control @error('admission_source') is-invalid @enderror"
                         placeholder="e.g. ER, Clinic Referral">
                  @error('admission_source')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
               <div class="col-md-4">
  <label class="form-label">Department <span class="text-danger">*</span></label>
<select id="department" name="department_id" class="form-select" required>
    <option value="">Choose…</option>
    @foreach($departments as $d)
        <option value="{{ $d->department_id }}" {{ old('department_id')==$d->department_id ? 'selected' : '' }}>
            {{ $d->department_name }}
        </option>
    @endforeach
</select>

</div>
             <div class="col-md-4">
  <label class="form-label">Attending Doctor <span class="text-danger">*</span></label>
  <select id="doctor" name="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror" required>
    <option value="">Choose department first…</option>
    @foreach($doctors as $doc)
      <option value="{{ $doc->doctor_id }}"
        {{ old('doctor_id') == $doc->doctor_id ? 'selected' : '' }}>
        {{ $doc->doctor_name }}
      </option>
    @endforeach
  </select>
  @error('doctor_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="col-md-4">
  <label class="form-label">Room <span class="text-danger">*</span></label>
  <select id="room" name="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
    <option value="">Choose department first…</option>
    @foreach($rooms as $room)
      <option value="{{ $room->room_id }}"
        {{ old('room_id') == $room->room_id ? 'selected' : '' }}>
        {{ $room->room_number }}
      </option>
    @endforeach
  </select>
  @error('room_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="col-md-4">
  <label class="form-label">Bed</label>
  <select id="bed" name="bed_id" class="form-select @error('bed_id') is-invalid @enderror">
    <option value="">Choose room first…</option>
    @foreach($beds as $bed)
      <option value="{{ $bed->bed_id }}"
        {{ old('bed_id') == $bed->bed_id ? 'selected' : '' }}>
        {{ $bed->bed_number }}
      </option>
    @endforeach
  </select>
  @error('bed_id')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

              
                <div class="col-12">
                  <label class="form-label">Admission Notes</label>
                  <textarea name="admission_notes" rows="3"
                            class="form-control @error('admission_notes') is-invalid @enderror"
                            placeholder="Any special instructions or notes">{{ old('admission_notes') }}</textarea>
                  @error('admission_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <button type="button" class="btn btn-secondary step-prev" data-current="admission" data-prev="medical">
                Previous
              </button>
              <button type="button" class="btn btn-primary step-next" data-current="admission" data-next="billing">
                Next
              </button>
            </div>
          </div>
        </div>

        {{-- 4. Billing Details --}}
        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
          <div class="card mb-4">
            <div class="card-header">
              <strong>Billing Details</strong>
              <p class="text-muted mb-0">Configure billing items, insurance, and initial payment</p>
            </div>
            <div class="card-body">  
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Insurance Provider</label>
                  <input type="text" name="insurance_provider" value="{{ old('insurance_provider') }}"
                         class="form-control @error('insurance_provider') is-invalid @enderror">
                  @error('insurance_provider')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Policy Number</label>
                  <input type="text" name="policy_number" value="{{ old('policy_number') }}"
                         class="form-control @error('policy_number') is-invalid @enderror">
                  @error('policy_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                  <label class="form-label">Initial Deposit (₱)</label>
                  <input type="number" step="0.01" name="initial_deposit" value="{{ old('initial_deposit') }}"
                         class="form-control @error('initial_deposit') is-invalid @enderror">
                  @error('initial_deposit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <button type="button" class="btn btn-secondary step-prev" data-current="billing" data-prev="admission">
                Previous
              </button>
        <button type="submit" class="btn btn-success">Save All Details</button>

            </div>
          </div>
        </div>


      </div>
    </form>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.step-next').forEach(btn => {
      btn.addEventListener('click', () => {
        const cur = btn.dataset.current;
        const nxt = btn.dataset.next;
        document.getElementById(cur + '-tab').classList.add('completed');
        new bootstrap.Tab(document.getElementById(nxt + '-tab')).show();
      });
    });

    document.querySelectorAll('.step-prev').forEach(btn => {
      btn.addEventListener('click', () => {
        const prev = btn.dataset.prev;
        new bootstrap.Tab(document.getElementById(prev + '-tab')).show();
      });
    });

    document.querySelectorAll('#patientTabs button[data-bs-toggle="tab"]').forEach(tab => {
      tab.addEventListener('show.bs.tab', e => {
        const order = ['personal','medical','admission','billing'];
        const idx = order.indexOf(e.target.id.replace('-tab',''));
        order.slice(idx+1).forEach(step => {
          document.getElementById(step + '-tab').classList.remove('completed');
        });
      });
    });
  });
const deptSelect = document.getElementById('department');
const docSelect  = document.getElementById('doctor');
const roomSelect = document.getElementById('room');
const bedSelect  = document.getElementById('bed');

deptSelect.addEventListener('change', async () => {
  const depId = deptSelect.value;
  if (!depId) return;

  // Fetch doctors
  let doctors = await fetch(`/admission/departments/${depId}/doctors`)
                    .then(res => res.json());
  docSelect.innerHTML = '<option value="">Select doctor…</option>';
  doctors.forEach(d => {
    docSelect.innerHTML += `<option value="${d.doctor_id}">${d.doctor_name}</option>`;
  });

  // Fetch rooms
  let rooms = await fetch(`/admission/departments/${depId}/rooms`)
                   .then(res => res.json());
  roomSelect.innerHTML = '<option value="">Select room…</option>';
  rooms.forEach(r => {
    roomSelect.innerHTML += `<option value="${r.room_id}">${r.room_number}</option>`;
  });

  // reset bed
  bedSelect.innerHTML = '<option value="">Select room first…</option>';
});

roomSelect.addEventListener('change', async () => {
  const roomId = roomSelect.value;
  if (!roomId) return;

  // Fetch beds
  let beds = await fetch(`/admission/rooms/${roomId}/beds`)
                  .then(res => res.json());
  bedSelect.innerHTML = '<option value="">Select bed…</option>';
  beds.forEach(b => {
    bedSelect.innerHTML += `<option value="${b.bed_id}">${b.bed_number}</option>`;
  });
});
  
</script>
@endsection
