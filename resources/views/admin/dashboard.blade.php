{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
  <h1 class="h3 mb-4">Admin Dashboard</h1>

  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Available Beds</h6>
          <h2 class="card-text">{{ $availableBeds }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Recent Admissions</h6>
          <h2 class="card-text">{{ $recentAdmissions->count() }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Pending Billings</h6>
          <h2 class="card-text">{{ $pendingBillings->count() }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    {{-- Recent Admissions --}}
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">Recent Admissions</div>
        <ul class="list-group list-group-flush">
          @forelse($recentAdmissions as $adm)
            <li class="list-group-item">
              {{ $adm->admission_date->format('M d, Y') }} —
              {{ $adm->patient->patient_first_name }} {{ $adm->patient->patient_last_name }}<br>
              Dr. {{ $adm->doctor->doctor_name }}
            </li>
          @empty
            <li class="list-group-item">No recent admissions.</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Pending Billings --}}
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header">Pending Billings</div>
        <ul class="list-group list-group-flush">
          @forelse($pendingBillings as $bill)
            <li class="list-group-item">
              {{ $bill->patient->patient_first_name }} {{ $bill->patient->patient_last_name }}<br>
              <small class="text-muted">Status: {{ $bill->payment_status ?? 'pending' }}</small>
            </li>
          @empty
            <li class="list-group-item">No pending billings.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
