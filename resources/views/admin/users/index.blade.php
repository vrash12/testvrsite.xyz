@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Manage Users</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
      <i class="fas fa-plus"></i> New User
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>#</th><th>Username</th><th>Email</th><th>Role</th><th width="150">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->user_id }}</td>
          <td>{{ $u->username }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ ucfirst($u->role) }}</td>
          <td>
            <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-outline-secondary">✎</a>
            <form method="POST" action="{{ route('admin.users.destroy',$u) }}" class="d-inline">
              @csrf @method('DELETE')
              <button onclick="return confirm('Delete?')" class="btn btn-sm btn-outline-danger">🗑</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
