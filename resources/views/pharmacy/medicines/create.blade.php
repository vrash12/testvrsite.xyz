@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">
  <h2 class="mb-4">Add New Medicine</h2>

  <form action="{{ route('pharmacy.medicines.store') }}" method="POST">
    @csrf

    {{-- Name --}}
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="service_name"
             value="{{ old('service_name') }}"
             class="form-control @error('service_name') is-invalid @enderror"
             required>
      @error('service_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Department --}}
    <div class="mb-3">
      <label class="form-label">Department</label>
      <select name="department_id"
              class="form-select @error('department_id') is-invalid @enderror" required>
        <option value="">Select…</option>
        @foreach($departments as $dept)
          <option value="{{ $dept->department_id }}"
             @selected(old('department_id') == $dept->department_id)>
            {{ $dept->department_name }}
          </option>
        @endforeach
      </select>
      @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Price --}}
    <div class="mb-3">
      <label class="form-label">Price (₱)</label>
      <input type="number" step="0.01" name="price"
             value="{{ old('price') }}"
             class="form-control @error('price') is-invalid @enderror"
             required>
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Initial Stock Quantity --}}
    <div class="mb-3">
      <label class="form-label">Initial Quantity</label>
      <input type="number" name="quantity"
             value="{{ old('quantity', 0) }}"
             class="form-control @error('quantity') is-invalid @enderror"
             min="0" required>
      @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Description --}}
    <div class="mb-3">
      <label class="form-label">Description (optional)</label>
      <textarea name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="3">{{ old('description') }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Actions --}}
    <div class="text-end">
      <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-success">Create</button>
    </div>
  </form>
</div>
@endsection
