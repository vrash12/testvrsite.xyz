{{-- resources/views/patient/notifications.blade.php --}}
@extends('layouts.patients')

@section('content')
<div class="container-fluid h-100 border p-4" style="background-color:#fafafa">
  <div class="mb-2">
    <h4>Notifications</h4>
    <p>Welcome to Notifications! Stay updated on your billing.</p>
  </div>

  <div class="alert alert-warning">
    <i class="fa-solid fa-circle-info me-2"></i><strong>Important!</strong> Do not share this information.
  </div>

  <div class="mb-3 text-end">
    <form action="{{ route('notifications.markAllRead') }}" method="POST">
      @csrf
      <button class="btn btn-sm btn-outline-secondary">Mark All as Read</button>
    </form>
  </div>

  <div class="card">
    <div class="card-body table-responsive" style="max-height:70vh">
      <table class="table table-hover align-middle">
        <thead class="table-light sticky-top">
          <tr>
            <th>Type</th>
            <th>Message</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notifications as $n)
            <tr class="{{ $n->read_at ? '' : 'table-warning' }}">
              <td>{{ $n->data['type'] }}</td>
              <td>{{ $n->data['title'] }}</td>
              <td>{{ $n->created_at->format('M d, Y') }}</td>
              <td>{{ $n->created_at->diffForHumans() }}</td>
              <td>
                <span class="badge bg-{{ $n->read_at ? 'success' : 'danger' }}">
                  {{ $n->read_at ? 'Read' : 'Unread' }}
                </span>
              </td>
              <td class="text-center">
                @if(! empty($n->data['billing_item_id']))
                  <button
                    class="btn btn-sm btn-outline-primary btn-details"
                    data-bs-toggle="modal"
                    data-bs-target="#detailsModal"
                    data-url="{{ route('patient.billing.chargeTrace', $n->data['billing_item_id']) }}">
                    View
                  </button>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">No notifications found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Charge Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs mb-3">
          <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-timeline" type="button">
              Timeline
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-details" type="button">
              Details
            </button>
          </li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-timeline">
            <p class="text-muted">Loading…</p>
          </div>
          <div class="tab-pane fade" id="tab-details">
            <p class="text-muted">Loading…</p>
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
  document.querySelectorAll('.btn-details').forEach(btn => {
    btn.addEventListener('click', async () => {
      const url = btn.dataset.url;
      document.getElementById('tab-timeline').innerHTML = '<p class="text-muted">Loading…</p>';
      document.getElementById('tab-details').innerHTML = '<p class="text-muted">Loading…</p>';
      try {
        const res  = await fetch(url);
        const html = await res.text();
        const doc  = new DOMParser().parseFromString(html, 'text/html');
        const tl   = doc.querySelector('#charge-trace-timeline');
        const dt   = doc.querySelector('#charge-trace-details');
        document.getElementById('tab-timeline').innerHTML = tl ? tl.innerHTML : '<p class="text-muted">No timeline.</p>';
        document.getElementById('tab-details').innerHTML = dt ? dt.innerHTML : '<p class="text-muted">No details available.</p>';
      } catch {
        document.getElementById('tab-timeline').innerHTML = '<p class="text-danger">Error loading data.</p>';
        document.getElementById('tab-details').innerHTML = '<p class="text-danger">Error loading data.</p>';
      }
    });
  });
});
</script>
@endpush
