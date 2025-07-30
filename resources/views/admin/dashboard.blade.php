@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
  <h1 class="h3 mb-4">Dashboard</h1>

  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Total Active Users</h6>
          <h2 class="card-text">{{ $totalActiveUsers }}</h2>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-center">
        <div class="card-body">
          <h6 class="card-title">Total Created Rooms</h6>
          <h2 class="card-text">{{ $totalCreatedRooms }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h6 class="card-title">Recently Created Users</h6>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentUsers as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center">No users found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
