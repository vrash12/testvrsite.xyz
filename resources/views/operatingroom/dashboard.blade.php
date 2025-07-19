{{--resources\views\operatingroom\dashboard.blade.php --}}'

@extends('layouts.operatingroom') 

@section('content')

     <div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-title">Procedures Given</div>
                    <div class="card-text">{{$proceduresGiven->count()}}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-title">Patients Served</div>
                    <div class="card-text">{{$patientsServed->count()}}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-title">IDK Anymore</div>
                    <div class="card-text">{{ }}</div>
                </div>
            </div>
        </div>
        
         {{-- Recent Transactions Table --}}
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="input-group w-50">
                    <input type="text" class="form-control" placeholder="Search by Name or MRN">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <a href="{{ route('') }}" class="btn btn-primary">Search</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>MRN #</th>
                            <th>Patient Name</th>
                            <th>Procedure</th>
                            <th>Room</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentORCompletion as $or_recent)
                            <tr>
                                <td>{{}}</td>
                                <td>{{}}</td>
                                <td>{{}}</td>
                                <td>{{}}</td>
                                <td>{{}}</td>
                                <td>{{}}</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">
                                        <i class="fas fa-file-alt"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No recent service completion.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection