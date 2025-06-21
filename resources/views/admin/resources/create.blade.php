{{-- resources/views/admin/resources/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1>Create Room & Bed</h1>
  <a href="{{ route('admin.resources.index') }}" class="btn btn-sm btn-secondary">
    ← Back to List
  </a>
</div>

<div class="row">

  {{-- New Room --}}
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">
        <strong>New Room</strong>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id"
                    class="form-select @error('department_id') is-invalid @enderror" required>
              <option value="">Select department…</option>
              @foreach($departments as $d)
                <option value="{{ $d->department_id }}"
                  {{ old('department_id') == $d->department_id ? 'selected' : '' }}>
                  {{ $d->department_name }}
                </option>
              @endforeach
            </select>
            @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" name="room_number" id="room_number"
                   value="{{ old('room_number') }}"
                   class="form-control @error('room_number') is-invalid @enderror"
                   placeholder="e.g. 101A" required>
            @error('room_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status"
                    class="form-select @error('status') is-invalid @enderror" required>
              <option value="available" {{ old('status')=='available'?'selected':'' }}>Available</option>
              <option value="unavailable" {{ old('status')=='unavailable'?'selected':'' }}>Unavailable</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="btn btn-primary">Create Room</button>
        </form>
      </div>
    </div>
  </div>

  {{-- New Bed --}}
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">
        <strong>New Bed</strong>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.beds.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id"
                    class="form-select @error('room_id') is-invalid @enderror" required>
              <option value="">Select room…</option>
              @foreach($rooms as $r)
                <option value="{{ $r->room_id }}"
                  {{ old('room_id') == $r->room_id ? 'selected' : '' }}>
                  {{ $r->room_number }} ({{ $r->department->department_name }})
                </option>
              @endforeach
            </select>
            @error('room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="bed_number" class="form-label">Bed Number</label>
            <input type="text" name="bed_number" id="bed_number"
                   value="{{ old('bed_number') }}"
                   class="form-control @error('bed_number') is-invalid @enderror"
                   placeholder="e.g. Bed 1" required>
            @error('bed_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status"
                    class="form-select @error('status') is-invalid @enderror" required>
              <option value="available" {{ old('status')=='available'?'selected':'' }}>Available</option>
              <option value="occupied" {{ old('status')=='occupied'?'selected':'' }}>Occupied</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="btn btn-secondary">Create Bed</button>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection
