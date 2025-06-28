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
        

    </div>

@endsection