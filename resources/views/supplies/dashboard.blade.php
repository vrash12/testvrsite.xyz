{{-- resources/views/supplies/dashboard.blade.php --}}
@extends('layouts.supplies')

@section('content')
<div class="container-fluid py-4 border" style="background-color: #FAFAFA">
  <h2 class="hdng mb-4">Supplies - Patient Management</h2>

  <div class="row mb-4">
    {{-- Number of Supplies Given --}}
    <div class="col-md-4">
      <h6 class="card-title">Supplies Given</h6>
      <p class="card-text">{{ $suppliesGiven->count() }}</p>
    </div>

    {{-- Unique Patients Served --}}
    <div class="col-md-4">
      <h6 class="card-title">Patients Served</h6>
      <p class="card-text">{{ $patientsServe }}</p>
    </div>

    {{-- Pending Orders --}}
    <div class="col-md-4">
      <h6 class="card-title">Pending Orders</h6>
      <p class="card-text">{{ $pendingOrders }}</p>
    </div>
  </div>

  <div class="row">
    {{-- Recent Served Supplies --}}
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">Recent Served Supplies</div>
        <ul class="list-group list-group-flush">
          @forelse($recentSupplies as $rcnt)
            <li class="list-group-item">
              {{ $rcnt->created_at->format('M d, Y h:ia') }}
              &mdash; {{ $rcnt->patient->patient_first_name }} {{ $rcnt->patient->patient_last_name }}
              &mdash; {{ $rcnt->service->service_name }}
              (×{{ $rcnt->quantity }})
            </li>
          @empty
            <li class="list-group-item">No recent supplies</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Most Served Supplies --}}
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header">Most Served Supplies</div>
        <ul class="list-group list-group-flush">
          @forelse($mostServedSupply as $most)
            <li class="list-group-item">
              {{ $most->service->service_name }} &mdash; {{ $most->total_qty }} units
            </li>
          @empty
            <li class="list-group-item">No supply data</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
