{{-- resources/views/patient/dashboard.blade.php --}}
@extends('layouts.patients')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h4 mb-3">Hello, {{ $user->username }}!</h1>
    <p>Welcome to your patient portal! A hub for patients to access medical records and bills anytime, anywhere.</p>

    <div class="row">
        {{-- Patient ID --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Patient ID</h5>
                    <p class="card-text">{{ str_pad($user->patient_id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- Room Number --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Room Number</h5>
                    <p class="card-text">{{ $admission->room_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Latest Admit Date --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Latest Admit Date</h5>
                    <p class="card-text">
                        {{ $admission && $admission->admission_date
                            ? $admission->admission_date->format('m/d/Y')
                            : 'N/A' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Amount Due --}}
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Amount Due</h5>
                   <div class="fs-5 fw-bold">
      ₱{{ number_format($amountDue, 2) }}
    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Prescriptions to Take --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Prescriptions to Take</h5>
                    @forelse($prescriptions as $item)
                        <div class="mb-2">
                            <strong>{{ $item->service->service_name }}</strong>
                            — {{ $item->dosage ?? 'Qty: '.$item->quantity_asked }}
                            <br>
                            <small class="text-muted">
                                Ordered on
                                {{ \Carbon\Carbon::parse($item->datetime)->format('m/d/Y h:i A') }}
                            </small>
                        </div>
                    @empty
                        <p>No prescriptions</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Today's Schedule --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Your Schedule Today</h5>
                    @forelse($todaySchedule as $sched)
                        <div class="mb-2">
                            <strong>{{ $sched->service->service_name }}</strong>
                            at {{ \Carbon\Carbon::parse($sched->datetime)->format('h:i A') }}
                            <br>
                            <small class="text-muted">{{ ucfirst($sched->service_status) }}</small>
                        </div>
                    @empty
                        <p>No appointments</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Assigned Doctors --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Assigned Doctors</h5>
                    @forelse($assignedDoctors as $doc)
                        <p>
                            {{ $doc->doctor_name }}
                            ({{ $doc->doctor_specialization }})
                        </p>
                    @empty
                        <p>No doctors assigned</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Pharmacy Charges --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Pharmacy Charges</h5>
                    @forelse($pharmacyCharges as $c)
                        <div class="mb-2">
                            <strong>{{ $c->service->service_name }}</strong>
                            — ₱{{ number_format($c->quantity_asked * $c->service->price, 2) }}
                            <br>
                            <small class="text-muted">
                                Ordered on
                                {{ \Carbon\Carbon::parse($c->datetime)->format('m/d/Y h:i A') }}
                            </small>
                        </div>
                    @empty
                        <p>No charges</p>
                    @endforelse

                    <hr/>
                    <p class="mb-0">
                        <strong>Total Pharmacy Charges:</strong>
                        ₱{{ number_format($pharmacyTotal, 2) }}
                    </p>
                </div>
            </div>
        </div>
        {{-- Scheduled / Completed Services --}}
<div class="card mb-3 shadow-sm">
  <div class="card-header bg-light fw-semibold">
    Hospital Services <span class="badge bg-primary">{{ $serviceAssignments->count() }}</span>
  </div>

  <div class="card-body p-0">
    @if($serviceAssignments->isEmpty())
        <p class="text-muted p-3 mb-0">No services ordered yet.</p>
    @else
      <table class="table table-sm mb-0">
        <thead>
          <tr>
            <th>Datetime</th>
            <th>Service</th>
            <th>Dept.</th>
            <th class="text-end">Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($serviceAssignments as $sa)
            <tr>
              <td>{{ \Carbon\Carbon::parse($sa->datetime)->format('M d, Y') }}</td>
              <td>{{ $sa->service->service_name }}</td>
              <td>{{ $sa->service->department->department_name ?? '—' }}</td>
              <td class="text-end">{{ number_format($sa->service->price, 2) }}</td>
              <td><span class="badge bg-{{ $sa->service_status === 'confirmed' ? 'success' : 'secondary' }}">
                    {{ ucfirst($sa->service_status) }}
                  </span>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="fw-semibold">
            <td colspan="3" class="text-end">Total</td>
            <td class="text-end">{{ number_format($servicesTotal, 2) }}</td>
            <td></ td>
          </tr>
        </tfoot>
      </table>
    @endif
  </div>
</div>

    </div>
</div>
@endsection
