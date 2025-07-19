{{--resources/views/admin/resources/index.blade.php--}}
@extends('layouts.admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Rooms & Beds</h1>
    <a href="{{ route('admin.resources.create') }}" class="btn btn-sm btn-primary">
      <i class="fas fa-door-open me-1"></i> Add Bed & Room
    </a>
  </div>

  <table class="table table-hover">
    <thead class="table-light">
      <tr>
        <th>Room #</th>
        <th>Department</th>
        <th>Total Beds</th>
        <th>Available Beds</th>
        <th>Beds (status)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rooms as $room)
        @php
          $total     = $room->beds->count();
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
                <li class="small d-flex align-items-center">
                  {{ $bed->bed_number }}
                  <span class="badge bg-{{ $bed->status==='available'?'success':'secondary' }} ms-1 me-2">
                    {{ ucfirst($bed->status) }}
                  </span>
                <a href="{{ route('admin.resources.edit', ['type'=>'bed','id'=>$bed->bed_id]) }}">
    ✎
</a>
                </li>
              @endforeach
            </ul>
          </td>
          <td class="d-flex gap-2">

            {{-- Edit Room --}}
         <a href="{{ route('admin.resources.edit', ['type'=>'room','id'=>$room->room_id]) }}">
    Edit
</a>

            {{-- Delete Room --}}
            <form action="{{ route('admin.resources.destroy', ['type'=>'room','id'=>$room->room_id]) }}"
                  method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Delete this room and its beds?')">
                Delete
              </button>
            </form>

          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
