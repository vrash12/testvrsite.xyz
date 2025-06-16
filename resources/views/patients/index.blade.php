@extends('layouts.admission') {{-- assumes you have an admission‐layout with sidebar, etc. --}}

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary">All Patients</h2>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
      <i class="fas fa-user-plus"></i> Add Patient
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Birthday</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($patients as $patient)
      <tr>
        <td>{{ $patient->patient_id }}</td>
        <td>{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</td>
        <td>{{ $patient->patient_birthday?->format('M d, Y') }}</td>
        <td>{{ ucfirst($patient->status) }}</td>
        <td>
          <a href="{{ route('patients.show', $patient) }}"
             class="btn btn-sm btn-primary">
            View
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection
