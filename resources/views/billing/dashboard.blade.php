@extends('layouts.billing')

@section('content')

 <div class="container-fluid min-vh-100 p-4 d-flex flex-column" style="background-color: #fafafa;">

            <!-- Header -->

            <header class="mb-2">
                <h4 class="hdng">Patient Billing Management</h4>
                <p>Welcome to Patient Billing Management, Manage patient billing records and disputes</p>
            </header>

            <!-- Metrics -->
            <div class="row g-3 mb-3">
                <div class="col col-lg-3 col-md-6 col-sm-12">
                    <div class="card border">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-4">
                                <i class="fa-solid fa-chart-simple fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Revenue</div>
                                <h5 class="mb-0">₱{{  }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-3 col-md-6 col-sm-12">
                    <div class="card border">
                        <div class="card-body d-flex align-items-center">
                            <div  class="me-4">
                                <i class="fa-solid fa-peso-sign fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Outstanding Balances</div>
                                <h5 class="mb-0">₱{{  }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-lg-3 col-md-6 col-sm-12">
                    <div class="card border">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-4">
                                <i class="fa-solid fa-bed fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Active Patients</div>
                                <h5 class="mb-0">{{  }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col col-lg-3 col-md-6 col-sm-12">
                    <div class="card border">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="fa-solid fa-person-circle-question fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Pending Disputes</div>
                                <h5 class="mb-0">{{  }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-plus me-2 text-primary"></i> Recent Billing Activity</h5>
                    </div>


                    <div class="table-responsive rounded shadow-sm p-1">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>MRN</th>
                                    <th>Patient</th>
                                    <th>Description</th>
                                    <th>Origin</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="fw-semibold">{{  }}</div>
                                        <small class="text-muted">ID: {{  }}</small>
                                    </td>
                                    <td>{{  }}</td>
                                    <td>
                                        <span class="badge bg-info">{{  }}</span>
                                    </td>
                                    <td class="text-end">₱{{  }}</td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-file-circle-question me-2"></i>Details
                                        </a>
                                    </td>
                                </tr>
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection