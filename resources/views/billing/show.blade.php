


{{-- resources/views/billing/show.blade.php --}}

@extends('layouts.billing')

@section('content')
<main class="p-4" style="margin-left: 240px;">
    <div class="container-fluid min-vh-100 p-4 d-flex flex-column">

        <!-- Patient Info -->
        <div class="card border mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fa-solid fa-bed me-2"></i>Patient Information</h5>
            </div>

            <div class="card-body d-flex align-items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="avatar rounded-circle bg-light border" style="width: 64px; height: 64px;"></div>
                </div>
                <div>
                    <strong class="fs-5">{{ $patient->full_name ?? 'Stanley Gonzales' }}</strong>
                    <div class="text-muted small">Patient ID: {{ $patient->id ?? '1234' }}</div>
                    <div class="text-muted small">Room: {{ $patient->room ?? '201-B' }}</div>
                   <div class="text-muted small">
  Admitted:
  {{ $patient->admitted_at ? $patient->admitted_at->format('F d, Y') : '—' }}
</div>

                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="card border mb-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-hospital-user me-2 text-primary"></i>Transaction History</h5>
                <button class="btn btn-primary"><i class="fa-solid fa-arrows-rotate me-2"></i>Refresh</button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive rounded shadow-sm" style="height: 450px; overflow-y: auto;">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Source Origin</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                     <tbody>
  <tr>
    <td>1</td>
    <td>{{ $charge->service->service_name ?? '—' }}</td>
    <td>
      <span class="badge rounded-pill bg-success">
        {{ ucfirst($charge->origin ?? 'manual') }}
      </span>
    </td>
    <td>{{ $charge->quantity ?? 1 }}</td>
    <td>₱{{ number_format($charge->unit_price ?? $charge->amount, 2) }}</td>
    <td class="text-end">
      ₱{{ number_format($charge->amount - ($charge->discount_amount ?? 0), 2) }}
    </td>
 <td class="text-center">
  {{-- Edit Charge --}}
  <a href="{{ route('billing.charges.edit',   $charge->billing_item_id) }}"
     class="btn btn-sm btn-outline-secondary"
     title="Edit Charge">
    <i class="fa-solid fa-pen"></i>
  </a>

  {{-- Delete / Void Charge --}}
  <form method="POST"
        action="{{ route('billing.charges.destroy', $charge->billing_item_id) }}"
        class="d-inline"
        onsubmit="return confirm('Really delete this charge?');">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="btn btn-sm btn-outline-danger"
            title="Delete Charge">
      <i class="fa-solid fa-trash"></i>
    </button>
  </form>

  {{-- View Audit Log / Timeline --}}
  <a href="{{ route('billing.charges.audit',   $charge->billing_item_id) }}"
     class="btn btn-sm btn-outline-info"
     title="View Audit Trail">
    <i class="fa-solid fa-clock-rotate-left"></i>
  </a>
</td>

  </tr>
</tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Totals Card -->
        <div class="card p-3 border shadow-sm">
            <div class="d-flex justify-content-between">
                <strong>Total Charges:</strong>
                <span class="fw-bold text-danger">₱{{ number_format($totalCharges, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Total Deposits:</strong>
                <span class="fw-bold text-success">₱{{ number_format($totalDeposits, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Balance:</strong>
                <span class="fw-bold {{ $balance < 0 ? 'text-success' : 'text-danger' }}">
                    ₱{{ number_format($balance, 2) }}
                </span>
            </div>
        </div>

    </div>
</main>

<!-- Sticky Bottom Action Bar -->
<div class="position-sticky bottom-0 start-0 end-0 bg-white border-top border p-3 z-3" style="margin-left: 240px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        
        <!-- Back -->
        <div>
            <a href="{{ route('patient.billing') }}" class="btn btn-primary">
                <i class="fa-solid fa-arrow-left me-2"></i>Back
            </a>
        </div>

   <div class="d-flex flex-wrap gap-2 justify-content-end">
  <a href="{{ route('billing.charges.create') }}" class="btn btn-outline-primary">
    <i class="fa-solid fa-plus me-1"></i> Manual Charge
  </a>

  <a href="{{ route('billing.deposits.create') }}" class="btn btn-outline-warning">
    <i class="fa-solid fa-money-bill-wave me-1"></i> Post Deposit
  </a>

  <a href="{{ route('billing.print',   $patient) }}" class="btn btn-outline-secondary">
    <i class="fa-solid fa-print me-1"></i> Print SOA
  </a>

  <form method="POST" action="{{ route('billing.lock',   $patient) }}">
    @csrf
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-lock me-1"></i> Lock Bill
    </button>
  </form>
</div>



    </div>
</div>
@endsection

