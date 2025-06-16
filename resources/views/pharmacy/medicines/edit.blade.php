@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">
  <h2 class="mb-4">Edit Medicine</h2>

  <form action="{{ route('pharmacy.medicines.update', $medicine) }}" method="POST">
    @csrf @method('PUT')

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="service_name"
             value="{{ old('service_name', $medicine->service_name) }}"
             class="form-control @error('service_name') is-invalid @enderror"
             required>
      @error('service_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Department</label>
      <select name="department_id"
              class="form-select @error('department_id') is-invalid @enderror" required>
        @foreach($departments as $dept)
          <option value="{{ $dept->department_id }}"
            @selected(old('department_id', $medicine->department_id)==$dept->department_id)>
            {{ $dept->department_name }}
          </option>
        @endforeach
      </select>
      @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Price (₱)</label>
      <input type="number" step="0.01" name="price"
             value="{{ old('price', $medicine->price) }}"
             class="form-control @error('price') is-invalid @enderror"
             required>
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Description (optional)</label>
      <textarea name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="3">{{ old('description', $medicine->description) }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="text-end">
      <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>
</div>
@endsection
