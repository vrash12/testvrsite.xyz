{{-- resources/views/patient/billing.blade.php --}}
@extends('layouts.patients')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">

        {{-- Heading & Admission selector --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-primary mb-1">Billing and Transactions</h4>
                <small class="text-muted">
                    Welcome! Here you can monitor an itemized version of your bill.
                </small>
            </div>

            <form method="GET">
                <select name="admission_id"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    @foreach($admissions as $adm)
                        <option value="{{ $adm->admission_id }}"
                            {{ $adm->admission_id == $admissionId ? 'selected' : '' }}>
                            {{ $adm->admission_date->format('M d, Y') }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Yellow info banner --}}
        <div class="alert alert-warning d-flex align-items-center py-2 mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                <strong>Important:</strong> Disputed items will appear with a red badge until resolved.
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-muted">Total Amount</div>
                    <div class="fs-5 fw-bold">₱{{ number_format($totals['total'],2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-muted">Balance</div>
                    <div class="fs-5 fw-bold">₱{{ number_format($totals['balance'],2) }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded p-3 h-100">
                    <div class="text-muted">Discount Applied</div>
                    <div class="fs-5 fw-bold">₱{{ number_format($totals['discount'],2) }}</div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" class="row g-2 mb-3">
            <input type="hidden" name="admission_id" value="{{ $admissionId }}">

            <div class="col-md-4">
                <input type="text"
                       name="q"
                       class="form-control form-control-sm"
                       placeholder="Search Billing Items"
                       value="{{ request('q') }}">
            </div>

            <div class="col-md-3">
                <select name="order" class="form-select form-select-sm">
                    <option value="desc" {{ request('order','desc')==='desc'?'selected':'' }}>Date (Newest)</option>
                    <option value="asc"  {{ request('order')==='asc'?'selected':''  }}>Date (Oldest)</option>
                </select>
            </div>

            <div class="col-md-1">
                <button class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Ref no.</th>
                        <th>Description</th>
                        <th>Provider</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->billing_date)->format('Y-m-d') }}</td>
                        <td>{{ $row->ref_no }}</td>
                        <td>{{ $row->description }}</td>
                        <td>{{ $row->provider }}</td>
                        <td class="text-end">₱{{ number_format($row->amount,2) }}</td>
                        <td>
                            @php
                                $badge = [
                                    'complete'  => 'success',
                                    'completed' => 'success',
                                    'pending'   => 'warning',
                                    'disputed'  => 'danger',
                                ][$row->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badge }} text-capitalize">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Details Modal Trigger -->
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm btn-details"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailsModal"
                                    data-bill-id="{{ $row->bill_id }}"
                                    data-description="{{ $row->description }}">
                                Details
                            </button>
                            <!-- Dispute Modal Trigger -->
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm btn-dispute"
                                    data-bs-toggle="modal"
                                    data-bs-target="#disputeModal"
                                    data-item-id="{{ $row->bill_id }}" {{-- or item_id if you have --}}
                                    data-date="{{ \Carbon\Carbon::parse($row->billing_date)->format('Y-m-d') }}"
                                    data-time="{{ \Carbon\Carbon::parse($row->billing_date)->format('h:ia') }}"
                                    data-ref="{{ $row->ref_no }}"
                                    data-description="{{ $row->description }}"
                                    data-provider="{{ $row->provider }}"
                                    data-amount="₱{{ number_format($row->amount,2) }}">
                                Request Review
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No billing items found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Download Statement --}}
        <div class="text-end mt-3">
            <a href="{{ route('patient.billing.statement', ['admission_id' => $admissionId]) }}"
               class="btn btn-primary btn-sm">
                <i class="fas fa-download me-1"></i> Download Statement
            </a>
        </div>

    </div>
</div>

{{-- ========================== DISPUTE MODAL ========================== --}}
<div class="modal fade" id="disputeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary fw-bold">Request for Clarification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="disputeForm"
            method="POST"
            action="{{ route('patient.disputes.store') }}"
            enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="bill_item_id" id="d-item-id">

        <div class="modal-body">
          {{-- Context panel --}}
          <div class="bg-light rounded p-3 mb-4">
            <div class="row">
              <div class="col-4 small"><strong>Date:</strong> <span id="d-date"></span></div>
              <div class="col-4 small"><strong>Reference:</strong> <span id="d-ref"></span></div>
              <div class="col-4 small"><strong>Amount:</strong> <span id="d-amount"></span></div>

              <div class="col-4 small"><strong>Time:</strong> <span id="d-time"></span></div>
              <div class="col-4 small"><strong>Description:</strong> <span id="d-desc"></span></div>
              <div class="col-4 small"><strong>Provider:</strong> <span id="d-prov"></span></div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Reason (Dispute Type)</label>
            <input type="text" name="reason" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Additional Details</label>
            <textarea name="details" rows="3" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Supporting Documents</label>
            <input type="file" name="documents[]" class="form-control" multiple>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>

    </div>
  </div>
</div>

{{-- ======================== DETAILS / TIMELINE MODAL ======================== --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Complete Charge History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab"
                    data-bs-target="#tab-timeline" type="button">Timeline</button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab"
                    data-bs-target="#tab-details" type="button">Charge Details</button>
          </li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-timeline">
            <div id="timeline-content">
              <p class="text-muted">Loading timeline…</p>
            </div>
          </div>

          <div class="tab-pane fade" id="tab-details">
            <div id="charge-details-content">
              <p class="text-muted">Loading details…</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Fill dispute modal
  document.querySelectorAll('.btn-dispute').forEach(btn => {
    btn.addEventListener('click', e => {
      const d = e.currentTarget.dataset;
      document.getElementById('d-item-id').value   = d.itemId;
      document.getElementById('d-date').textContent  = d.date;
      document.getElementById('d-ref').textContent   = d.ref;
      document.getElementById('d-amount').textContent= d.amount;
      document.getElementById('d-time').textContent  = d.time;
      document.getElementById('d-desc').textContent  = d.description;
      document.getElementById('d-prov').textContent  = d.provider;
    });
  });

  // Fill details modal (placeholder data)
  document.querySelectorAll('.btn-details').forEach(btn => {
    btn.addEventListener('click', e => {
      const bid = e.currentTarget.dataset.billId;
      const desc = e.currentTarget.dataset.description;

      document.getElementById('timeline-content').innerHTML = `
        <ul class="list-group mb-3">
          <li class="list-group-item">
            <span class="badge bg-secondary rounded-circle me-2">C</span>
            Charge Created – ${desc}
            <div class="small text-muted">2025-04-11 16:00 by Dr. Sarah Johnson • Cardiology</div>
          </li>
          <li class="list-group-item">
            <span class="badge bg-success rounded-circle me-2">V</span>
            Charge Verified
            <div class="small text-muted">2025-04-11 16:30 by Maria Rodriguez • Billing</div>
          </li>
        </ul>`;

      document.getElementById('charge-details-content').innerHTML = `
        <table class="table table-sm">
          <tr><th>Description</th><td>${desc}</td></tr>
          <tr><th>Bill ID</th><td>${bid}</td></tr>
          <tr><th>Status</th><td>Complete</td></tr>
          <tr><th>Department</th><td>Cardiology</td></tr>
        </table>`;
    });
  });
});
</script>
@endpush
