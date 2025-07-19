{{-- resources/views/patients/create.blade.php --}}
@extends('layouts.admission')

@section('content')
  <div class="page-header mb-4">
    <h1 class="h3">New Patient Admission</h1>
  </div>

  <form method="POST" action="{{ route('admission.patients.store') }}" novalidate>
    @include('patients._form')
    <div class="mt-4 text-end">
      <a href="{{ route('admission.patients.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-success">Save</button>
    </div>
  </form>
@endsection
