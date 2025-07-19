{{-- resources/views/pharmacy/charges/create.blade.php --}}

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
          <option value="{{ $p->patient_id }}" @selected(old('patient_id') == $p->patient_id)>
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
                  {{ $s->service_name }} – ₱{{ number_format($s->price,2) }}
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
                   class="form-control @error('medications.0.quantity') is-invalid @enderror" required>
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
        <select name="prescribing_doctor" 
                class="form-select @error('prescribing_doctor') is-invalid @enderror" required>
          <option value="">Select doctor…</option>
          @foreach($doctors as $doc)
            <option value="{{ $doc->doctor_name }}" @selected(old('prescribing_doctor') == $doc->doctor_name)>
              {{ $doc->doctor_name }}
            </option>
          @endforeach
        </select>
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
                class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
      @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Total Display --}}
    <div class="mb-4 text-end">
      <strong>Total Amount:</strong> ₱<span id="grand-total">0.00</span>
    </div>

    <div class="text-end">
      <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">Create Charge</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  (function() {
    let idx = 1;
    const list = document.getElementById('medications-list');

    // Add new medication block
    document.getElementById('add-medication').addEventListener('click', () => {
      const tpl = list.querySelector('.medication-item').cloneNode(true);
      tpl.querySelector('h6').textContent = `Medication #${idx + 1}`;

      // Re-index only elements that have a name attribute
      tpl.querySelectorAll('[name]').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
        if (!el.matches('select')) {
          el.value = '';
        }
      });

      // Clear unit/total fields
      tpl.querySelector('.unit-price').value = '';
      tpl.querySelector('.total-price').value = '';

      list.append(tpl);
      idx++;
    });

    // Helper to recalc a single line and the grand total
    function updateLine(item) {
      const sel   = item.querySelector('select[name*="[service_id]"]');
      const qty   = item.querySelector('input[name*="[quantity]"]');
      const unit  = item.querySelector('.unit-price');
      const total = item.querySelector('.total-price');

      const price   = parseFloat(sel.selectedOptions[0]?.dataset.price || 0);
      const qtyVal  = parseInt(qty.value) || 0;
      unit.value    = price.toFixed(2);
      total.value   = (price * qtyVal).toFixed(2);

      // Grand total
      let gt = 0;
      document.querySelectorAll('.total-price').forEach(el => {
        gt += parseFloat(el.value) || 0;
      });
      document.getElementById('grand-total').textContent = gt.toFixed(2);
    }

    // Delegate changes for service or quantity
    list.addEventListener('change', e => {
      if (e.target.matches('select[name*="[service_id]"], input[name*="[quantity]"]')) {
        updateLine(e.target.closest('.medication-item'));
      }
    });
  })();
</script>
@endpush
