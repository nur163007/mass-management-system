@extends('admin.master')

@section('heading', 'View Rooms')
@section('title', 'view rooms')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Rooms</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.room.assign')}}" class="viewall"><i class="fas fa-user-plus"></i> Assign Member</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row">
                        @foreach($rooms as $room)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title font-weight-bold">{{ $room->room_name }}</h3>
                                    <div class="card-tools">
                                        <span class="badge badge-info">Rent: Tk. {{ number_format($room->monthly_rent, 2) }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted"><strong>Capacity:</strong> {{ $room->capacity }} members</p>
                                    <p class="text-muted"><strong>Current Members:</strong> {{ $room->roomMembers->count() }} / {{ $room->capacity }}</p>
                                    
                                    @if($room->roomMembers->count() > 0)
                                    <hr>
                                    <h6 class="font-weight-bold">Members:</h6>
                                    <ul class="list-unstyled">
                                        @foreach($room->roomMembers as $roomMember)
                                        <li class="mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>
                                                    <i class="fas fa-user"></i> {{ $roomMember->member->full_name }}
                                                    @if($roomMember->member->role_id == 2)
                                                        <span class="badge badge-warning badge-sm">Manager</span>
                                                    @endif
                                                </span>
                                                <a href="{{ route('admin.room.show', $room->id) }}" class="btn btn-info btn-xs">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-muted text-center">No members assigned</p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin.room.show', $room->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-info-circle"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($rooms->count() == 0)
                    <div class="alert alert-info text-center">
                        <h5>No rooms found</h5>
                        <p>Please initialize rooms first.</p>
                        <a href="{{ route('admin.room.initialize') }}" class="btn btn-primary">Initialize Rooms</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

