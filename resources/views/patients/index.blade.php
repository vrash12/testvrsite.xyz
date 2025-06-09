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
        <th>Last Name</th>
        <th>First Name</th>
        <th>Birthday</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($patients as $patient)
        <tr>
          <td>{{ $patient->patient_id }}</td>
          <td>{{ $patient->patient_last_name }}</td>
          <td>{{ $patient->patient_first_name }}</td>
          <td>{{ optional($patient->patient_birthday)->format('Y-m-d') }}</td>
          <td>{{ $patient->email }}</td>
          <td>{{ $patient->phone_number }}</td>
          <td>
            {{-- You could add Edit/View buttons here --}}
            <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
            <a href="#" class="btn btn-sm btn-outline-warning">Edit</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
