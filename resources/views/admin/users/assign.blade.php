@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
  <h1 class="h3 mb-4">Assign: {{ $user->username }}</h1>

  <form method="POST" action="{{ route('admin.users.assign.update', $user) }}">
    @csrf

    <div class="mb-3">
      <label class="form-label">Department</label>
      <select id="department" name="department_id"
              class="form-select @error('department_id') is-invalid @enderror">
        <option value="">— select department —</option>
        @foreach($departments as $dep)
          <option value="{{ $dep->department_id }}"
            @selected($user->department_id == $dep->department_id)>
            {{ $dep->department_name }}
          </option>
        @endforeach
      </select>
      @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Room</label>
      <select id="room" name="room_id"
              class="form-select @error('room_id') is-invalid @enderror">
        <option value="">— select room —</option>
        @foreach($rooms as $r)
          <option value="{{ $r->room_id }}"
            @selected($user->room_id == $r->room_id)>
            {{ $r->room_number }}
          </option>
        @endforeach
      </select>
      @error('room_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Bed</label>
      <select id="bed" name="bed_id"
              class="form-select @error('bed_id') is-invalid @enderror">
        <option value="">— select bed —</option>
        @foreach($beds as $b)
          <option value="{{ $b->bed_id }}"
            @selected($user->bed_id == $b->bed_id)>
            {{ $b->bed_number }}
          </option>
        @endforeach
      </select>
      @error('bed_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="text-end">
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">Save Assignments</button>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const deptSelect = document.getElementById('department');
  const roomSelect = document.getElementById('room');
  const bedSelect  = document.getElementById('bed');

  deptSelect.addEventListener('change', async () => {
    const depId = deptSelect.value;
    // fetch rooms for this department
    let rooms = await fetch(`/admission/departments/${depId}/rooms`)
                 .then(r => r.json());
    roomSelect.innerHTML = '<option value="">— select room —</option>';
    rooms.forEach(r => {
      roomSelect.innerHTML +=
        `<option value="${r.room_id}">${r.room_number}</option>`;
    });
    // reset beds
    bedSelect.innerHTML = '<option value="">— select bed —</option>';
  });

  roomSelect.addEventListener('change', async () => {
    const roomId = roomSelect.value;
    let beds = await fetch(`/admission/rooms/${roomId}/beds`)
               .then(r => r.json());
    bedSelect.innerHTML = '<option value="">— select bed —</option>';
    beds.forEach(b => {
      bedSelect.innerHTML +=
        `<option value="${b.bed_id}">${b.bed_number}</option>`;
    });
  });
});
</script>
@endsection
