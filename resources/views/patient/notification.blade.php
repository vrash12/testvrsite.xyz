@extends('layouts.patients')

@section('content')

    <div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">

        <div class="mb-2">
            <h4 class="hdng">Notifications</h4>
            <p>Welcome to Notifications! Here you can stay updated on things about your bill</p>
        </div>

        <div>
            @if ($notifications->isEmpty())
                <div class="alert alert-info m-2">You have no notifications yet.</div>
            @else
            <div class="card">
                <div class="card-body table-responsive" style="max-height: 70vh">
                    <table class="table table-hover align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                {{-- Type = Billing, Admission, Dispute --}}
                               <th>Type</th>
                               <th>Message</th>
                               <th>Date</th>
                               <th>Time</th>
                               <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notif)
                                <tr class="{{ $notif->read ? '' : 'table-warning' }}">
                                    <td><i class="fa-solid fa-circle-dot text-primary"></i> {{ $notif->type }}</td>
                                    <td>{{$notif->title }} </td>
                                    <td>{{$notif->created_at->format('M d, Y h:i A') }}</td>
                                    <td>{{ $notif->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($notif->read)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-danger">Unread</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer text-end">
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                         @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark All as Read</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
