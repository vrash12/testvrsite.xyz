{{-- resources/views/supplies/queue.blade.php --}}

@extends('layouts.supplies')

@section('content')

     <div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">

            {{-- Heading --}}
            <div>
                <h1 class="hdng">Patient - Supply Requests Queue</h1>
                <p>Confirm requests completion and all</p>
            </div>

            {{-- Search Bar | Filter --}}
            <form  method='GET' action="">
            <div class="row border py-4 mx-1">
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-3"></i>Filter Results</button>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select" name="date_range">
                        <option value="">Default</option>
                        <option value="asc" {{ request('date_range') == 'asc' ? 'selected' : ''}}>Ascending</option>
                        <option value="desc" {{ request('date_range') == 'desc' ? 'selected' : ''}}>Descending</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" placeholder="Search by Name or MRN" name="search" value="{{ request('search') }}">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
            </form>

            {{-- Table --}}
            <div class="row border my-2 mx-1">
               <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Entry Date</th>
                                <th>MRN</th>
                                <th>Patient Name</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Assigned By (Doctor)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentMiscRequest as $miscReq)
                                <tr>
                                    <td>{{$miscReq->entry_date->format('Y-m-d')}}</td>
                                    <td>{{$miscReq->patient->patientID}}</td>
                                    <td>{{$miscReq->patient->patient_first_name}} {{$miscReq->patient->patient_last_name}}</td>
                                    <td>{{$miscReq->item_name}}</td>
                                    <td>{{$miscReq->quantity}}</td>
                                    <td>{{$miscReq->doctor->doctor_name}}</td>
                                    <td><span class="badge bg-{{  $miscReq->status == 'pending' ? 'warning text-dark' : 'success' }}">
                                       {{ ucfirst($miscReq->status) }}
                                    </span></td>
                                    <td><a href="{{ route('') }}" class="btn btn-sm btn-outline-success">
                                        <i class="fa-solid fa-check"></i> Confirm
                                    </a></td>
                                </tr>
                            @empty 
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No recent admissions.</td>
                            </tr>   
                            @endforelse
                        </tbody>
                        <tbody id="completed-row">
                            {{-- Completed Requests Below --}}
                        </tbody>
                    </table>
               </div>
            </div>
    </div>

@push('scripts')
    <script>
        // Confirm Approvals Dialog Box, Change Status
        const cfBtn = document.querySelectorAll('.confirm-btn');

        cfBtn.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const row = btn.closest('tr'); 

                Swal.fire({
                    title: "Are you sure?",
                    text: "You wont be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor:"#00529A",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirm"
                }).then((result)=>{
                    if(result.isConfirmed){
                        // Update Status Badge: Pending -> Completed
                        const statusBadge = row.querySelector('.badge');
                        statusBadge.classList.remove('bg-warning', 'text-dark');
                        statusBadge.classList.add('bg-success', 'text-white');
                        statusBadge.textContent = 'Completed';

                         // Remove Confirm Button
                        btn.remove();

                        // Move completed Rows Below
                        const completedBody = document.getElementById('completed-row');
                        completedBody.appendChild(row);

                        // Success Alert
                        Swal.fire({
                            title: "Confirmed",
                            text: "Request marked as completed.",
                            icon: "success"                       
                        })
                    }
                })
            });
        });

    </script>
@endpush
@endsection


{{-- 

Needs Improvement for
- Confirm Dialog Box
- Pending - Completed Status 
- Filter Does not work

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif


@if($miscReq->status == 'pending')
  <a href="{{ route('supplies.markCompleted', $miscReq->id) }}" class="btn btn-sm btn-outline-success">
    <i class="fa-solid fa-check"></i> Confirm
  </a>
@else
  <span class="text-muted"><i class="fa-solid fa-check-double"></i> Completed</span>
@endif



--}}