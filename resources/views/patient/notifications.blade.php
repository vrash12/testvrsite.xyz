{{-- resources/views/patient/notifications.blade.php --}}
@extends('layouts.patients')

@section('content')
<div class="card shadow-sm">
  <div class="card-body p-4">

    {{-- Title & Subtitle --}}
    <h4 class="fw-bold text-primary mb-1">Notifications</h4>
    <small class="text-muted mb-3 d-block">
      Welcome to notifications! get alerts about status of your health and bill!
    </small>

    {{-- Filter dropdown --}}
    <form method="GET" class="mb-3">
      <select name="filter"
              class="form-select form-select-sm w-auto"
              onchange="this.form.submit()">
        <option value="All"    {{ $filter==='All'    ? 'selected' : '' }}>All</option>
        <option value="read"   {{ $filter==='read'   ? 'selected' : '' }}>Read</option>
        <option value="unread" {{ $filter==='unread' ? 'selected' : '' }}>Unread</option>
      </select>
    </form>

    {{-- Table of notifications --}}
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead class="table-light">
          <tr>
            <th>Notification</th>
            <th>Date</th>
            <th>Time</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notifications as $n)
            @php $dt = $n->created_at; @endphp
            <tr>
              <td>{{ $n->data['message'] ?? $n->data['title'] ?? 'Notification' }}</td>
              <td>{{ $dt->format('Y-m-d') }}</td>
              <td>{{ $dt->format('h:ia') }}</td>
              <td class="text-center">
                <form method="POST"
                      action="{{ route('patient.notifications.update', $n->id) }}">
                  @csrf @method('PATCH')
                  <input type="checkbox"
                         name="read"
                         onChange="this.form.submit()"
                         {{ $n->read_at ? 'checked' : '' }}>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted">
                No notifications.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
