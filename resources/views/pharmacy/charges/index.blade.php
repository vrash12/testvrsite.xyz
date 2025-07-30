{{-- resources/views/pharmacy/charges/index.blade.php --}}
@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">

  {{-- ────────────────────── Heading & New button ────────────────────── --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Medication Charges</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChargeModal">
      <i class="fas fa-plus me-1"></i> New Charge
    </button>
  </div>

  {{-- ─────────────────────────── Charges table ─────────────────────────── --}}
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Patient</th>
              <th>Rx&nbsp;#</th>
              <th class="text-end">Total</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($charges as $ch)
              <tr>
                <td>{{ $ch->id }}</td>
                <td>{{ $ch->created_at->format('Y-m-d') }}</td>
                <td>{{ $ch->patient->patient_first_name }} {{ $ch->patient->patient_last_name }}</td>
                <td>{{ $ch->rx_number }}</td>
              <td class="text-end">₱{{ number_format($ch->items->sum('total'),2) }}</td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-secondary btn-show"
                          data-url="{{ route('pharmacy.charges.show',$ch) }}">
                    View
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- pagination --}}
      <div class="p-2">
        {{ $charges->links() }}
      </div>
    </div>
  </div>
</div>

{{-- ───────────────────────────── View-only modal ───────────────────────────── --}}
<div class="modal fade" id="showChargeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Medication Charge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted">Loading…</p>
      </div>
    </div>
  </div>
</div>

{{-- ───────────────────────────── New-charge modal ───────────────────────────── --}}
<div class="modal fade" id="newChargeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Add New Medication Charge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('pharmacy.charges.store') }}">
        @csrf
        <div class="modal-body">

          {{-- Patient select --}}
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

          {{-- Prescribing doctor --}}
          <div class="mb-3">
            <label class="form-label">Prescribing Doctor</label>
            <input type="text" name="prescribing_doctor"
                   value="{{ old('prescribing_doctor') }}"
                   class="form-control @error('prescribing_doctor') is-invalid @enderror" required>
            @error('prescribing_doctor')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Rx number --}}
          <div class="mb-3">
            <label class="form-label">Rx Number</label>
            <input type="text" name="rx_number"
                   value="{{ old('rx_number') }}"
                   class="form-control @error('rx_number') is-invalid @enderror" required>
            @error('rx_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Medications repeater --}}
          <div id="medications-wrapper">
            <div class="row g-2 mb-2 medication-row">
              <div class="col-6">
                <select name="medications[0][service_id]" class="form-select" required>
                  <option value="">Select medication…</option>
                  @foreach($services as $s)
                    <option value="{{ $s->service_id }}">{{ $s->service_name }} (₱{{ number_format($s->price,2) }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <input type="number" min="1" name="medications[0][quantity]" class="form-control" placeholder="Qty" required>
              </div>
              <div class="col-3 d-grid">
                <button type="button" class="btn btn-danger btn-remove">Remove</button>
              </div>
            </div>
          </div>
          <button type="button" id="btn-add-med" class="btn btn-outline-primary btn-sm mb-3">
            Add another medication
          </button>

          {{-- Notes --}}
          <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Charge</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
/* ─── Modal helpers ──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {

  /* View-charge modal */
  document.querySelectorAll('.btn-show').forEach(btn => {
    btn.addEventListener('click', async () => {
      const url = btn.dataset.url;
      const modalBody = document.querySelector('#showChargeModal .modal-body');
      modalBody.innerHTML = '<p class="text-muted">Loading…</p>';
      new bootstrap.Modal(document.getElementById('showChargeModal')).show();

      try {
        const html = await (await fetch(url, { headers:{'X-Requested-With':'XMLHttpRequest'} })).text();
        modalBody.innerHTML = html;
      } catch (e) {
        modalBody.innerHTML = '<p class="text-danger">Failed to load charge.</p>';
      }
    });
  });

  /* Add-medication repeater */
  const wrapper = document.getElementById('medications-wrapper');
  document.getElementById('btn-add-med').addEventListener('click', () => {
    const idx = wrapper.querySelectorAll('.medication-row').length;
    const tmpl = `
      <div class="row g-2 mb-2 medication-row">
        <div class="col-6">
          <select name="medications[${idx}][service_id]" class="form-select" required>
            <option value="">Select medication…</option>
            @foreach($services as $s)
              <option value="{{ $s->service_id }}">{{ $s->service_name }} (₱{{ number_format($s->price,2) }})</option>
            @endforeach
          </select>
        </div>
        <div class="col-3">
          <input type="number" min="1" name="medications[${idx}][quantity]" class="form-control" placeholder="Qty" required>
        </div>
        <div class="col-3 d-grid">
          <button type="button" class="btn btn-danger btn-remove">Remove</button>
        </div>
      </div>`;
    wrapper.insertAdjacentHTML('beforeend', tmpl);
  });

  wrapper.addEventListener('click', e => {
    if (e.target.classList.contains('btn-remove')) {
      e.target.closest('.medication-row').remove();
    }
  });
});
</script>
@endpush
