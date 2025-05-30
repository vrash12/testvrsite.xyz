@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">My Profile</h1>
        <p class="text-gray-600">Edit your profile and change your password.</p>
    </div>

    <!-- Patient Profile -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="font-medium">First Name</label>
                <p>{{ $patient->first_name }}</p>
            </div>
            <div>
                <label class="font-medium">Last Name</label>
                <p>{{ $patient->last_name }}</p>
            </div>
            <div>
                <label class="font-medium">Email</label>
                <p>{{ $patient->email }}</p>
            </div>
            <div>
                <label class="font-medium">Phone Number</label>
                <p>{{ $patient->phone_number }}</p>
            </div>
            <div>
                <label class="font-medium">Birthday</label>
                <p>{{ $patient->birthday->format('M d, Y') }}</p>
            </div>
        </div>
        <a href="{{ route('patient.profile.edit') }}" class="mt-4 text-blue-600">Edit Profile</a>
    </div>

    <!-- Change Password Section -->
    <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
        <a href="{{ route('patient.profile.change-password') }}" class="text-blue-600">Change Password</a>
    </div>
</div>
@endsection
