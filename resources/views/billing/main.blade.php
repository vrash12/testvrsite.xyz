@extends('layouts.billing')

@section('content')

         <div class="container-fluid min-vh-100 p-4 d-flex flex-column" style="background-color: #fafafa;">

                <!-- Header -->
                <header class="mb-2">
                    <h4 class="hdng">Patient Billing Management</h4>
                    <p>Welcome to Patient Billing Management, Manage patient billing records and disputes</p>
                </header>

                <!-- Search Menu -->
                <div class="card border mb-3">
                    <div class="card-body">
                        <div class="card-title"><strong>Search Menu</strong></div>
                        <div class="row d-flex g-3">
                            <div class="col col-sm-12 col-md-8 col-lg-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="MRN or Patient Name">
                                    <button  class="btn btn-outline-primary"><i class="fa-solid fa-bolt me-2"></i>Search</button>
                                </div>
                            </div>
                            <div class="col col-sm-12 col-md-4 col-lg-2">
                                <select class="form-select">
                                    <option selected>Default</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card boder">
                    <div class="card-body table-responsive">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold"><i class="fa-solid fa-hospital-user me-2 text-primary"></i> Patient List</h5>
                        </div>
                        <div class="table-responsive rounded shadow-sm">
                          <table class="table align-middle table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>MRN</th>
                                    <th>Patient</th>
                                    <th>Amount Payed</th>
                                    <th>Amount Due</th>
                                    <th class="text-end">Balance</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($collection as $item)
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="fw-semibold">{{  }}</div>
                                            <small class="text-muted">ID: {{ }}</small>
                                        </td>
                                        <td>₱{{ }}</td>
                                        <td>₱{{ }}</td>
                                        <td class="text-end">₱{{  }}</td>
                                        <td class="text-center">
                                            <a href="" class="btn btn-outline-secondary">
                                                <i class="fa-solid fa-eye me-2"></i></i>View
                                            </a>
                                            <a href="" class="btn btn-outline-warning">
                                                <i class="fa-solid fa-file-circle-question me-2"></i>Edit
                                            </a>
                                    </td>
                                    </tr>
                                    @empty
                                        <td colspan="6" class="text-center text-muted">No Information Found</td>
                                    @endforelse
                                </tr>
                            </tbody>
                         </table>
                        </div>
                    </div>
                </div>

            </div>

@endsection