@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <h1 class="h3 mb-4">Create User</h1>

  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label class="form-label">Username</label>
      <input name="username" value="{{ old('username') }}"
             class="form-control @error('username') is-invalid @enderror">
      @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input name="email" type="email" value="{{ old('email') }}"
             class="form-control @error('email') is-invalid @enderror">
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select @error('role') is-invalid @enderror">
        <option value="">Select role…</option>
        @foreach($roles as $r)
          <option value="{{ $r }}" @selected(old('role')==$r)>{{ ucfirst($r) }}</option>
        @endforeach
      </select>
      @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input name="password" type="password"
               class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input name="password_confirmation" type="password" class="form-control">
      </div>
    </div>

    <div class="mt-4 text-end">
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">Create</button>
    </div>
  </form>
</div>
@endsection
