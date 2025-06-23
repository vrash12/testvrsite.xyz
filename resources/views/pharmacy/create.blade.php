@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">
  <h1 class="h4 mb-3">Add New Medication Charge</h1>

  <form method="POST" action="{{ route('pharmacy.charges.store') }}">
    @csrf

    {{-- Patient Select --}}
    <div class="mb-3">
      <label class="form-label">Patient</label>
      <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
        <option value="">Select patient…</option>
        @foreach($patients as $p)
          <option value="{{ $p->patient_id }}" @selected(old('patient_id')==$p->patient_id)>
            {{ $p->patient_first_name }} {{ $p->patient_last_name }}
          </option>
        @endforeach
      </select>
      @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Dynamic Medications --}}
    <div id="medications-list">
      <div class="medication-item mb-3">
        <h6>Medication #1</h6>
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Medication</label>
            <select name="medications[0][service_id]" 
                    class="form-select @error('medications.0.service_id') is-invalid @enderror" required>
              <option value="">Select medication…</option>
              @foreach($services as $s)
                <option value="{{ $s->service_id }}" data-price="{{ $s->price }}">
                  {{ $s->department->department_name }} – ₱{{ number_format($s->price,2) }}
                </option>
              @endforeach
            </select>
            @error('medications.0.service_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity</label>
            <input type="number" name="medications[0][quantity]" min="1" 
                   class="form-control quantity-input @error('medications.0.quantity') is-invalid @enderror" required>
            @error('medications.0.quantity')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-2">
            <label class="form-label">Unit Price</label>
            <input type="text" readonly class="form-control-plaintext border border-info unit-price">
          </div>
          <div class="col-md-2">
            <label class="form-label">Total</label>
            <input type="text" readonly class="form-control-plaintext border border-warning total-price">
          </div>
        </div>
      </div>
    </div>

    <button type="button" id="add-medication" class="btn btn-sm btn-outline-secondary mb-4">
      + Add Medication
    </button>

    {{-- Prescribing Doctor & RX --}}
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label">Prescribing Doctor</label>
        <input type="text" name="prescribing_doctor" 
               class="form-control @error('prescribing_doctor') is-invalid @enderror" required>
        @error('prescribing_doctor')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">RX Number</label>
        <input type="text" name="rx_number" 
               class="form-control @error('rx_number') is-invalid @enderror" required>
        @error('rx_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    {{-- Notes --}}
    <div class="mb-3">
      <label class="form-label">Notes (Optional)</label>
      <textarea name="notes" rows="3" 
                class="form-control @error('notes') is-invalid @enderror"></textarea>
      @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Total Display --}}
    <div class="mb-4 text-end">
      <strong>Total Amount:</strong> ₱<span id="grand-total">0.00</span>
    </div>

    <div class="text-end">
      <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">Create Charge</button>
    </div>
  </form>
</div>

{{-- Simple JS to clone medication blocks & compute totals --}}
@push('scripts')
<script>
  let idx = 1;
  document.getElementById('add-medication').addEventListener('click', () => {
    const tpl = document.querySelector('.medication-item').cloneNode(true);
    tpl.querySelector('h6').textContent = `Medication #${idx + 1}`;
    tpl.querySelectorAll('select, input').forEach(el => {
      const name = el.getAttribute('name').replace(/\[\d+\]/, `[${idx}]`);
      el.name = name;
      if (el.type !== 'select-one') el.value = '';
    });
    document.getElementById('medications-list').append(tpl);
  });
  // (Omitted: JS to pull unit-price from data-price on select change, multiply qty, update totals)
</script>
@endpush
@endsection
