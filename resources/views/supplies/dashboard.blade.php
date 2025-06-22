@extends('layouts.supplies')

@section('content')

    {{-- Module can assign charges even without doctor approval --}}
    {{-- Charges here are ff: Dextrose, Misc, Extra Wardrobe, Equipments --}}

    <div class="container-fluid py-4 border" style="background-color: #FAFAFA">

        <h2 class="hdng mb-4">Supplies - Patient Management</h2>

        <div class="row mb-4">
            {{-- Number of Supplies Given to Patients --}}
            <div class="col-md-4">
                <h6 class="card-title">Supplies Given</h6>
                <p class="card-text">{{$suppliesGiven->count()}}</p>
            </div>

            {{-- Number of Patients served --}}
            <div class="col-md-4">
                <h6 class="card-title">Patients Serve</h6>
                <p class="card-text">{{$patientsServe->count()}}</p>            
            </div>

            {{-- Number of Pending Orders that are assigned --}}
            <div class="col-md-4">
                <h6 class="card-title">Pending Orders</h6>
                <p class="card-text">{{}}</p>
            </div>
        </div>

        {{-- Edit ko to in table form | DATE/TIME | PATIENT | ITEM | --}}
        <div class="row">
            {{-- RECENT SERVED SUPPLIES:  --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recent Served Supplies</div>
                    <ul class="list-group list-group-flush">
                        @foreach ($recentSupplies as $rcnt_supply)
                            <li class="list-group-item">
                                {{  }}
                                {{  }}
                                {{  }}
                            </li>
                        @empty
                            <li class="list-group-item">No Recent</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            {{-- MOST SUPPLIES SERVE: | ITEM | COUNT |--}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Most Served Supplies</div>
                    <ul class="list-group list-group-flush">
                        {{-- I'll edit this --}} 
                        @foreach ($mostServedSupply as $most) 
                            <li class="list-group-item">
                                {{  }}
                                {{  }}
                                {{  }}
                            </li>
                        @empty
                            <li class="list-group-item">No Recent</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection