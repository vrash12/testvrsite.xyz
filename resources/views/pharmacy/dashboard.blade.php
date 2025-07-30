{{-- resources/views/pharmacy/dashboard.blade.php --}}
@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">

  {{-- Page header + “New” button --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Pharmacy Dashboard</h1>
    <a href="{{ route('pharmacy.charges.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> New Medication Charge
    </a>
  </div>

  {{-- KPI cards --}}
  <div class="row gy-3 mb-4">
    <div class="col-md-4">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Medication Charges</h6>
          <h2 class="fw-bold">{{ $totalCharges }}</h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Patients Served</h6>
          <h2 class="fw-bold">{{ $patientsServed }}</h2>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Pending Charges</h6>
          <h2 class="fw-bold">{{ $pendingCharges }}</h2>
        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
      <input name="q" type="text"
             value="{{ request('q') }}"
             class="form-control"
             placeholder="Search patient or Rx#">
    </div>
    <div class="col-md-3">
      <input name="from" type="date"
             value="{{ request('from') }}"
             class="form-control">
    </div>
    <div class="col-md-3">
      <input name="to" type="date"
             value="{{ request('to') }}"
             class="form-control">
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-outline-secondary">
        <i class="fas fa-filter"></i> Filter
      </button>
    </div>
  </form>

  {{-- Today’s Charges --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <span>Recent Medication Charges • {{ now()->format('M d, Y') }}</span>
      <span class="badge bg-primary">{{ $todayCharges->count() }}</span>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Rx#</th>
            <th>Patient</th>
            <th>Medications</th>
            <th class="text-end">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($todayCharges as $c)
            <tr>
              <td>{{ $c->created_at->format('M d, Y') }}</td>
              <td>{{ $c->rx_number }}</td>
              <td>{{ $c->patient->patient_first_name }} {{ $c->patient->patient_last_name }}</td>
              <td>{{ $c->items->pluck('service.service_name')->join(', ') }}</td>
              <td class="text-end">
                ₱{{ number_format($c->items->sum('total'),2) }}
              </td>
              <td class="text-end">
                {{-- Button that triggers the modal --}}
                <button type="button"
                        class="btn btn-sm btn-outline-secondary btn-show-charge"
                        data-url="{{ route('pharmacy.charges.show', $c) }}">
                  <i class="fas fa-eye"></i>
                </button>
              </td>
              <td class="text-end">
    @if($c->status === 'pending')
        <form method="POST"
              action="{{ route('pharmacy.charges.dispense', $c) }}"
              onsubmit="return confirm('Mark as dispensed?')">
            @csrf
            @method('PATCH')
            <button class="btn btn-success btn-sm">
                <i class="fas fa-check"></i> Dispense
            </button>
        </form>
    @else
        <span class="badge bg-success">Completed</span>
    @endif
</td>

            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-3">
                No charges today.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Earlier Charges --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <span>Earlier</span>
      <span class="badge bg-secondary">{{ $earlierCharges->count() }}</span>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Rx#</th>
            <th>Patient</th>
            <th>Medications</th>
            <th class="text-end">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($earlierCharges as $c)
            <tr>
              <td>{{ $c->created_at->format('M d, Y') }}</td>
              <td>{{ $c->rx_number }}</td>
              <td>{{ $c->patient->patient_first_name }} {{ $c->patient->patient_last_name }}</td>
              <td>{{ $c->items->pluck('service.service_name')->join(', ') }}</td>
              <td class="text-end">
                ₱{{ number_format($c->items->sum('total'),2) }}
              </td>
              <td class="text-end">
                <button type="button"
                        class="btn btn-sm btn-outline-secondary btn-show-charge"
                        data-url="{{ route('pharmacy.charges.show', $c) }}">
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-3">
                No earlier charges.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ================= Show-Charge Modal ================= --}}
<div class="modal fade" id="chargeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Medication Charge Details</h5>

      </div>
      <div class="modal-body">
        <p class="text-center text-muted">Loading...</p>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('chargeModal');
  const modal    = new bootstrap.Modal(modalEl);
  const body     = modalEl.querySelector('.modal-body');

  document.querySelectorAll('.btn-show-charge').forEach(btn => {
    btn.addEventListener('click', async () => {
      const url = btn.dataset.url;
      body.innerHTML = '<p class="text-center text-muted">Loading...</p>';
      modal.show();

      try {
        // fetch the partial view via AJAX
        const res  = await fetch(url, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const html = await res.text();
        body.innerHTML = html;
      } catch (err) {
        body.innerHTML = '<p class="text-danger text-center">Failed to load details.</p>';
        console.error(err);
      }
    });
  });
});
</script>
@endpush
