{{-- resources/views/pharmacy/charges/show.blade.php --}}
<div>
  {{-- Header --}}
  <div class="d-flex justify-content-between mb-3">
    <h5 class="mb-0">Medication Charge — Rx #{{ $charge->rx_number }}</h5>
 <span class="fs-5 fw-bold">
   Total ₱{{ number_format($charge->items->sum('total'), 2) }}
 </span>
  </div>

  {{-- Line items --}}
  <table class="table table-sm align-middle">
    <thead class="table-light">
      <tr>
        <th>Medication</th>
        <th class="text-center">Qty</th>
        <th class="text-end">Unit Price</th>
        <th class="text-end">Line Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($charge->items as $item)
        <tr>
          <td>{{ optional($item->service)->service_name ?: '—' }}</td>
          <td class="text-center">{{ $item->quantity }}</td>
          <td class="text-end">₱{{ number_format($item->unit_price, 2) }}</td>
          <td class="text-end">₱{{ number_format($item->total,      2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Footer --}}
  <div class="text-end mt-3">
    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
      Close
    </button>
    <a href="{{ route('pharmacy.charges.index') }}" class="btn btn-sm btn-primary">
      Back to list
    </a>
  </div>
</div>
