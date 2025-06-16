@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Charge Details</h1>
    <a href="{{ route('pharmacy.index') }}" class="btn btn-secondary">
      ← Back to List
    </a>
  </div>

  {{-- Charge Header --}}
  <div class="card mb-4">
    <div class="card-header">
      Charge #{{ $charge->id }} — {{ $charge->date->format('M d, Y') }}
    </div>
    <div class="card-body">
      <p><strong>Patient:</strong>
        {{ $charge->patient->patient_first_name }}
        {{ $charge->patient->patient_last_name }}
      </p>
      <p><strong>Prescribing Doctor:</strong> {{ $charge->prescribing_doctor }}</p>
      <p><strong>RX Number:</strong> {{ $charge->rx_number }}</p>
    </div>
  </div>

  {{-- Items --}}
  <div class="card mb-4">
    <div class="card-header">Medications Charged</div>
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Medicine</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($charge->items as $i => $item)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $item->medication_name }}</td>
              <td>{{ $item->quantity }}</td>
              <td>₱{{ number_format($item->unit_price,2) }}</td>
              <td>₱{{ number_format($item->quantity * $item->unit_price,2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-end">Grand Total</th>
            <th>₱{{ number_format($charge->total_amount,2) }}</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  {{-- Notes --}}
  @if($charge->notes)
    <div class="card mb-4">
      <div class="card-header">Notes</div>
      <div class="card-body">
        <p>{{ $charge->notes }}</p>
      </div>
    </div>
  @endif
</div>
@endsection
