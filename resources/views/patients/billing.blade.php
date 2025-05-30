@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-semibold mb-4">Billing and Transactions</h1>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @foreach ($billings as $billing)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $billing->date }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $billing->description }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">₱{{ number_format($billing->amount, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $billing->status }}</td>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('patient.billing.show', $billing->id) }}" class="text-blue-600">View</a>
                    <a href="{{ route('patient.billing.request-review', $billing->id) }}" class="text-red-600 ml-4">Request Review</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
