{{-- resources/views/admin/dashboard.blade.php --}}
@include('layouts.sidebar-admin')

@section('content')
<div class="min-h-screen bg-gray-100">


    <div class="flex-1 p-8">
        {{-- Page Header --}}
        <div class="max-w-7xl mx-auto mb-6">
            <h1 class="text-2xl font-bold">Dashboard</h1>
        </div>

        {{-- Stats Cards --}}
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Patients --}}
            <div class="bg-white rounded-lg shadow p-6 flex items-center">
                <div class="p-4 bg-gray-100 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M15 7a4 4 0 11-8 0 4 4 0 018 0zM21 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase">Total Patients</p>
                    <p class="text-3xl font-bold">{{ \App\Models\Patient::count() }}</p>
                </div>
            </div>

            {{-- New Admissions Today --}}
            <div class="bg-white rounded-lg shadow p-6 flex items-center">
                <div class="p-4 bg-gray-100 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3M15 13a3 3 0 11-6 0 3 3 0 016 0zM9 17v2m0 0v2m0-2h-2m2 0h2" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase">New Admissions (Today)</p>
                    <p class="text-3xl font-bold">{{ \App\Models\AdmissionDetail::whereDate('created_at', today())->count() }}</p>
                </div>
            </div>

            {{-- Available Beds --}}
            <div class="bg-white rounded-lg shadow p-6 flex items-center">
                <div class="p-4 bg-gray-100 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 13H4a2 2 0 00-2 2v5h2v-3h16v3h2v-5a2 2 0 00-2-2zM6 10a4 4 0 018 0v3H6v-3zm4-6a4 4 0 00-4 4h8a4 4 0 00-4-4z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 text-sm font-medium uppercase">Available Beds</p>
                    <p class="text-3xl font-bold">{{ $availableBeds }}</p>
                </div>
            </div>
        </div>

        {{-- Recent Admissions Table --}}
        <div class="max-w-7xl mx-auto bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium">Recent Admissions</h2>
                <a href="{{ route('admin.admissions.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <span class="text-lg">+</span> Admit new patient
                </a>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAdmissions as $adm)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $adm->patient->patient_first_name }} {{ $adm->patient->patient_last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $adm->doctor->doctor_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $adm->admission_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $adm->room_number }}/{{ $adm->bed_number }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pending Billings Table --}}
        <div class="max-w-7xl mx-auto bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-medium">Pending Billings</h2>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Due</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Policy #</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingBillings as $bill)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bill->patient->patient_first_name }} {{ $bill->patient->patient_last_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                    ₱{{ number_format($bill->amount_due,2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bill->policy_number ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
