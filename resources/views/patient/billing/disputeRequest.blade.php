
{{-- chargeTrace and disputeRequest under main Bill for patient--}}


{{-- resources/views/patient/billing/disputeRequest.blade.php --}}

@extends('layouts.patients')

@section('content')
<main class="p-4" style="margin-left: 240px; background-color: #f9fafe;">
  <div class="container-fluid min-vh-100 d-flex flex-column">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="hdng mb-0">Billing & Transactions</h4>
        <p class="text-muted mb-0">Monitor your charges and submit billing disputes</p>
      </div>
    </div>

    {{-- Charge Summary --}}
    <div class="card mb-4">
      <div class="card-body">
        <h6 class="mb-3 text-secondary"><i class="fa fa-receipt me-1"></i> Charge Summary</h6>
        <div class="row">
          <div class="col-md-6 mb-2">
            <small class="text-muted">Entry Date/Time</small>
            <div class="fw-semibold">{{ }}</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Prescripted By</small>
            <div class="fw-semibold">{{ }}</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Charge Origin</small>
            <div class="fw-semibold">{{ }}</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Item</small>
            <div class="fw-semibold">{{ }}</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Approved By</small>
            <div class="fw-semibold">{{ }}</div>
          </div>
          <div class="col-md-6 mb-2">
            <small class="text-muted">Amount</small>
            <div class="fw-semibold text-success">₱{{ }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Dispute Form --}}
    <div class="card mb-4">
      <div class="card-body">
        <h6 class="mb-3 text-secondary"><i class="fa fa-comment-dots me-1"></i> Submit a Review Request</h6>
        <form method="POST" action="{{ }}" enctype="multipart/form-data">
          @csrf
          
          <div class="mb-3">
            <label class="form-label">Reason for Dispute</label>
            <select class="form-select @error('reason_code') is-invalid @enderror" name="reason_code" required>
              <option value="">🔍 Select reason…</option>
              <option value="not_received">❌ Service not received</option>
              <option value="coding_error">🔢 Incorrect code</option>
              <option value="duplicate">📄 Duplicate</option>
              <option value="wrong_qty">🔁 Wrong quantity</option>
              <option value="cancelled">🚫 Cancelled service</option>
              <option value="other">📝 Others</option>
            </select>
            @error('reason_code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Evidence (Optional)</label>
            <input type="file" class="form-control @error('evidence') is-invalid @enderror" name="evidence" accept="image/*,.pdf">
            <small class="text-muted">Attach screenshots, receipts, or any relevant documents.</small>
            @error('evidence')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Explanation</label>
            <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="4" required placeholder="Explain why you are disputing this charge...">{{ old('reason') }}</textarea>
            @error('reason')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">
              <i class="fa fa-paper-plane me-1"></i> Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Navigation --}}
    <div class="d-flex justify-content-between align-items-center mt-2">
      <div>
        <a href="{{ route('patient.billing') }}" class="btn btn-outline-primary">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
      </div>
      <div>
        <a href="#" class="text-decoration-none small text-muted">
          <i class="fa fa-clock me-1"></i> View Request History
        </a>
      </div>
    </div>

  </div>
</main>
@endsection

