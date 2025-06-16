{{-- resources/views/pharmacy/dashboard.blade.php --}}
@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">

  {{-- Page Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pharmacy Dashboard</h1>
  </div>

  {{-- KPIs --}}
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Total Medication Charges</h6>
          <h2 class="card-text">{{ $totalCharges }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Patients Served</h6>
          <h2 class="card-text">{{ $patientsServed }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Pending Charges</h6>
          <h2 class="card-text">{{ $pendingCharges }}</h2>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Charges Table --}}
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Recent Medication Charges</h6>
      <a href="{{ route('pharmacy.charges.create') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> New Medication Charge
      </a>
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>RX#</th>
            <th>Patient</th>
            <th>Medication</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentCharges as $c)
            <tr>
              <td>{{ $c->date->format('M d, Y') }}</td>
              <td>{{ $c->rx_number }}</td>
              <td>{{ $c->patient->patient_first_name }} {{ $c->patient->patient_last_name }}</td>
              <td>{{ $c->items->pluck('medication_name')->join(', ') }}</td>
              <td>₱{{ number_format($c->total_amount,2) }}</td>
              <td>
                <a href="{{ route('pharmacy.charges.show',$c) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="fas fa-eye"></i> Details
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-3">No recent charges.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer text-center">
      <a href="{{ route('pharmacy.index') }}" class="small">View all charges →</a>
    </div>
  </div>

</div>
@endsection
