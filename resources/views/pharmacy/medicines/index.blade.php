{{-- resources/views/pharmacy/medicines/index.blade.php --}}
@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between mb-3">
    <h2>Medicines</h2>
    <!-- Button triggers the Add Medicine modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMedicineModal">
      <i class="fas fa-plus"></i> Add Medicine
    </button>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Name</th>
        <th>Department</th>
        <th class="text-end">Price</th>
        <th class="text-center">Quantity</th>
        <th width="150">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($medicines as $med)
        <tr>
          <td>{{ $med->service_name }}</td>
          <td>{{ $med->department->department_name }}</td>
          <td class="text-end">₱{{ number_format($med->price, 2) }}</td>
          <td class="text-center">{{ $med->quantity ?? 0 }}</td>
          <td>
            <a href="{{ route('pharmacy.medicines.edit', $med) }}" 
               class="btn btn-sm btn-outline-secondary">✎</a>
            <form method="POST"
                  action="{{ route('pharmacy.medicines.destroy', $med) }}"
                  class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Delete this medicine?')">
                🗑
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $medicines->links() }}
</div>

{{-- Add Medicine Modal --}}
<div class="modal fade" id="addMedicineModal" tabindex="-1" aria-labelledby="addMedicineLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('pharmacy.medicines.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="addMedicineLabel">Add New Medicine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
            @foreach(\App\Models\Department::orderBy('department_name')->get() as $dept)
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

        {{-- Initial Quantity --}}
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Create Medicine</button>
      </div>
    </form>
  </div>
</div>
@endsection
