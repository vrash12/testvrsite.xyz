{{-- resources/views/supplies/show.blade.php --}}

@extends('layouts.supplies')

@section('content')

    <div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">
        
        {{-- Heading --}}
        <div>
            <h1 class="hdng">Summary</h1>
            <p>Kindly review the summary details below before approval</p>
        </div>
        
        {{-- Information --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-hashtag me-2"></i><strong>TRANSACTION NO</strong>
                    </div>
                    <div class="card-body">
                       {{ $charge->created_at->format('M d, Y') }}

                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-bed me-2"></i><strong>PATIENT</strong>
                    </div>
                    <div class="card-body">
                        {{$charge->patient->patient_first_name}}
                        {{$charge->patient->patient_last_name}}
                    </div>
                </div>
            </div>
      <div class="col-md-4">
  <div class="card">
    <div class="card-header">
      <i class="fa-solid fa-user-doctor me-2"></i><strong>DOCTOR ASSIGNED</strong>
    </div>
    <div class="card-body">
      {{ optional(optional($charge->patient->admissionDetail)->doctor)->doctor_name ?? '—' }}
    </div>
  </div>
</div>

        </div>

    {{-- List of Charges --}}
<div class="card mb-4">
  <div class="card-header">
    <i class="fa-solid fa-receipt me-2"></i><strong>LIST OF CHARGES</strong>
  </div>
  <div class="card-body p-0">
    <form method="POST" action="{{ route('supplies.checkout', $charge->id) }}" id="form">
      @csrf
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>ITEM NAME</th>
            <th>QUANTITY</th>
            <th>UNIT PRICE</th>
            <th>SUBTOTAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>{{ $charge->service->service_name }}</td>
            <td>{{ $charge->quantity }}</td>
            <td>₱{{ number_format($charge->unit_price,2) }}</td>
            <td>₱{{ number_format($charge->total,2) }}</td>
          </tr>
          <tr>
            <td colspan="5" class="text-center fw-bold text-muted">** Nothing Follows **</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-end">Total</th>
            <th>₱{{ number_format($charge->total,2) }}</th>
          </tr>
        </tfoot>
      </table>
    </form>
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

        {{-- Navigation --}}
 <a href="{{ route('supplies.queue') }}" class="btn btn-outline-secondary me-2">Back</a>    
    <button class="btn btn-primary checkout-btn">
        <i class="fa-solid fa-circle-check me-2"></i>Checkout
    </button>

    </div>

@push('scripts')

    <script>
         // Checkout SweetAlert Confirmation
        const checkout = document.querySelector('.checkout-btn');

        checkout.addEventListener('click', (e)=>{
            e.preventDefault();

               Swal.fire({
                title: "Are you sure?",
                text: "You are about to submit these charges.",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, checkout!"
              }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submitted!',
                        text: 'Charges have been submitted.',
                        timer: 1500,
                        showConfirmButton: false
                    })

                    document.getElementById('form').submit();
                }
            });
        });
    </script>

@endpush
@endsection