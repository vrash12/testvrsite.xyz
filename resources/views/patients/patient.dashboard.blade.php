@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center mb-4">
        <div class="w-24 h-24 rounded-full bg-gray-200 mr-4">
            <img src="{{ $patient->profile_picture ?? asset('images/default-avatar.png') }}" class="w-full h-full rounded-full object-cover" alt="Profile">
        </div>
        <div>
            <h2 class="text-xl font-semibold">Hello, {{ $patient->first_name }} {{ $patient->last_name }}!</h2>
            <p class="text-gray-600">Welcome to your patient portal. Here you can access your medical records and bills anytime, anywhere.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Patient ID</h3>
            <p>{{ $patient->patient_id }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Room Number</h3>
            <p>{{ $patient->room_number }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Latest Admit Date</h3>
            <p>{{ \Carbon\Carbon::parse($patient->admit_date)->format('m/d/Y') }}</p>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Amount Due</h3>
            <p>{{ '₱' . number_format($patient->amount_due, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
        <!-- Prescriptions -->
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Prescriptions To Take</h3>
            @foreach($prescriptions as $prescription)
                <p>{{ $prescription->medication }} - {{ $prescription->dose }} ({{ $prescription->frequency }})</p>
            @endforeach
        </div>

        <!-- Schedule -->
        <div class="bg-white p-4 shadow rounded">
            <h3 class="text-lg font-semibold">Your Schedule Today</h3>
            @foreach($schedules as $schedule)
                <p>{{ $schedule->department }} - {{ $schedule->service }} at {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</p>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-4 shadow rounded mt-6">
        <h3 class="text-lg font-semibold">Assigned Doctors</h3>
        @foreach($assignedDoctors as $doctor)
            <p>{{ $doctor->name }} - {{ $doctor->specialization }}</p>
        @endforeach
    </div>
</div>
@endsection
