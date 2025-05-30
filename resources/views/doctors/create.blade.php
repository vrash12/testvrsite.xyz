@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">Doctor Registration</h2>

    <form action="{{ route('doctors.register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="doctor_name">Doctor's Name</label>
            <input type="text" class="form-control" name="doctor_name" required>
        </div>

        <div class="form-group">
            <label for="doctor_specialization">Specialization</label>
            <input type="text" class="form-control" name="doctor_specialization" required>
        </div>

        <button type="submit" class="btn btn-primary">Register Doctor</button>
    </form>
</div>
@endsection
