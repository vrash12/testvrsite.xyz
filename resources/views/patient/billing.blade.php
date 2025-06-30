{{-- resources/views/patient/billing.blade.php --}}

{{-- I'll worry mobile responsiveness later. --}}

@extends('layouts.patients')

@section('content')

    <div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">

        {{-- Top --}}
        <div class="mb-2 d-flex justify-content-center align-items-center">
            <div class="col">
                <h4 class="hdng">Patient Billing and Transactions</h4>
                <p>Welcome to Billing and Transactions! here you can monitor your bill!</p>
            </div>
            <div class="col-auto">
                <select class="form-select border border-primary">
                    <option value="default" selected>Default</option>
                </select>
            </div>
        </div>

        {{-- Caution --}}
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i><strong>Important!</strong> Do not share this information with anyone else.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        {{-- Metrics --}}
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">₱{{  }}</h4>
                        <p class="card-text">Amount Due</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">₱{{  }}</h4>
                        <p class="card-text">Amount Payed</p>  
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">₱{{  }}</h4>
                        <p class="card-text">Discount Applied</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="row flex-grow-1 overflow-hidden">
            <div class="table-responsive" style="max-height: 425px; overflow-y: auto;">
                <table  class="table table-striped table-hover">
                    <thead class="table-primary position-sticky top-0">
                        <tr>
                            <th style="width: 7%;">Date</th>
                            <th style="width: 7%;">Time</th>
                            <th style="width: 20%">Description</th>
                            <th style="width: 15%;">Provider</th>
                            <th style="width: 10%;">Amount</th>
                            <th style="width: 11%;">Status</th>
                            <th style="width: 30%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bills as $bill_item)
                            <tr>
                                <td>{{  }}</td>
                                <td>{{  }}</td>
                                <td>{{  }}</td>
                                <td>{{  }}</td>
                                <td>{{  }}</td>
                                <td>
                                    <span class="badge bg-{{  $bill_item->status == 'pending' ? 'warning text-dark' : 'verified' }}">
                                       {{ ucfirst($bill_item->status) }}
                                    </span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="" class="btn btn-outline-primary btn-sm text-nowrap"><i class="fa-solid fa-bolt me-2"></i>Show Details</a>
                                    <a href="" class="btn btn-outline-danger btn-sm text-nowrap"><i class="fa-solid fa-comment-medical me-2"></i>Request Review</a>
                                </td>
                            </tr>    
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No recent charges.</td>
                            </tr>   
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Download Billing Statement --}}
        <div class="mt-auto d-flex justify-content-end">
            <a href="{{ route('billing.download') }}" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-down me-2"></i>Download Statement</a>
        </div>       
    
    {{-- End --}}
    </div>

@endsection