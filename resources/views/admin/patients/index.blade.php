@extends('layouts.app')

@section('content')
<div class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Patients</h1>
            <a href="{{ route('admissions.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                New Patient
            </a>
        </div>

        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Phone
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Admission Date
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($patients as $patient)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->patient_first_name }} {{ $patient->patient_last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->phone_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection