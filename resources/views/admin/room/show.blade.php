@extends('admin.master')

@section('heading', 'Room Details')
@section('title', 'room details')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Room Details: {{ $room->room_name }}</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.room.index')}}" class="viewall"><i class="fas fa-arrow-left"></i> Back to Rooms</a>
                        <a href="{{route('admin.room.assign')}}" class="viewall"><i class="fas fa-user-plus"></i> Assign Member</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Room Name</span>
                                    <span class="info-box-number">{{ $room->room_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Monthly Rent</span>
                                    <span class="info-box-number">Tk. {{ number_format($room->monthly_rent, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Capacity</span>
                                    <span class="info-box-number">{{ $room->capacity }} members</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Current Members</span>
                                    <span class="info-box-number">{{ $room->roomMembers->count() }} / {{ $room->capacity }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title font-weight-bold">Room Members</h3>
                        </div>
                        <div class="card-body">
                            @if($room->roomMembers->count() > 0)
                            <table class="table table-bordered table-hover">
                                <thead class="bg-cyan">
                                    <tr>
                                        <th>SL NO</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Assigned Date</th>
                                        <th>Advance Paid</th>
                                        <th>Monthly Rent</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($room->roomMembers as $index => $roomMember)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $roomMember->member->full_name }}
                                            @if($roomMember->member->role_id == 2)
                                                <span class="badge badge-warning">Manager</span>
                                            @endif
                                        </td>
                                        <td>0{{ $roomMember->member->phone_no }}</td>
                                        <td>{{ $roomMember->member->email }}</td>
                                        <td>{{ $roomMember->assigned_date ? date('d M Y', strtotime($roomMember->assigned_date)) : 'N/A' }}</td>
                                        <td>Tk. {{ number_format($roomMember->advance_paid, 2) }}</td>
                                        <td>Tk. {{ number_format($roomMember->monthly_rent, 2) }}</td>
                                        <td>
                                            <form action="{{ route('admin.room.remove', $roomMember->member_id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                <input type="hidden" name="refund_amount" value="{{ $roomMember->advance_paid }}">
                                                <input type="hidden" name="refund_date" value="{{ date('Y-m-d') }}">
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to remove {{ $roomMember->member->full_name }} from this room? Refund amount: Tk. {{ number_format($roomMember->advance_paid, 2) }}');">
                                                    <i class="fas fa-times"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-info text-center">
                                <h5>No members assigned to this room</h5>
                                <a href="{{ route('admin.room.assign') }}" class="btn btn-primary">Assign Member</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
<script>
    $(function() {
        $(".table").DataTable();
    });
</script>
@endsection

