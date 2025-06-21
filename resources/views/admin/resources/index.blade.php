@extends('layouts.admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Rooms & Beds</h1>
    <div>
      <a href="{{ route('admin.resources.create') }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-door-open me-1"></i> Add Bed and Room
      </a>

    </div>
  </div>

  <table class="table table-hover">
    <thead class="table-light">
      <tr>
        <th>Room #</th>
        <th>Department</th>
        <th>Total Beds</th>
        <th>Available Beds</th>
        <th>Beds (status)</th>
        <th>Room Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rooms as $room)
        @php
          $total = $room->beds->count();
          $available = $room->beds->where('status','available')->count();
        @endphp
        <tr>
          <td>{{ $room->room_number }}</td>
          <td>{{ $room->department->department_name }}</td>
          <td>{{ $total }}</td>
          <td>{{ $available }}</td>
          <td>
            <ul class="mb-0 ps-3">
              @foreach($room->beds as $bed)
                <li class="small">
                  {{ $bed->bed_number }}
                  <span class="badge bg-{{ $bed->status==='available'?'success':'secondary' }} ms-1">
                    {{ ucfirst($bed->status) }}
                  </span>
                  <a href="{{ route('admin.resources.edit',$bed) }}" class="text-decoration-none ms-2">✎</a>
                </li>
              @endforeach
            </ul>
          </td>
          <td>
            <a href="{{ route('admin.resources.edit',$room) }}" class="btn btn-sm btn-outline-secondary">
              Edit
            </a>
            <form action="{{ route('admin.rooms.destroy',$room) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Delete this room?')">
                Delete
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
