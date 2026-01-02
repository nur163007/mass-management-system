@extends('admin.master')

@section('heading', 'Assign Member to Room')
@section('title', 'assign member')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Assign Member to Room</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.room.index')}}" class="viewall"><i class="fas fa-arrow-left"></i> Back to Rooms</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <form action="{{ route('admin.room.assign.store') }}" method="POST" id="form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="room_id">Room</label>
                                <select class="form-control select2bs4" id="room_id" name="room_id" required autocomplete="off">
                                    <option value="">--Select Room--</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" data-rent="{{ $room->monthly_rent }}" data-capacity="{{ $room->capacity }}">
                                            {{ $room->room_name }} (Rent: Tk. {{ number_format($room->monthly_rent, 2) }}, Capacity: {{ $room->capacity }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('room_id'))
                                    <p class="text-danger">{{ $errors->first('room_id') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="member_id">Member</label>
                                <select class="form-control select2bs4" id="member_id" name="member_id" required autocomplete="off">
                                    <option value="">--Select Member--</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">
                                            {{ $member->full_name }} 
                                            @if($member->role_id == 2)
                                                (Manager)
                                            @endif
                                            - {{ $member->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('member_id'))
                                    <p class="text-danger">{{ $errors->first('member_id') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="advance_amount">Advance Amount (Tk.)</label>
                                <input type="number" class="form-control" id="advance_amount" name="advance_amount" placeholder="Enter advance amount" step="0.01" min="0" required autocomplete="off">
                                @if ($errors->has('advance_amount'))
                                    <p class="text-danger">{{ $errors->first('advance_amount') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required autocomplete="off">
                                @if ($errors->has('payment_date'))
                                    <p class="text-danger">{{ $errors->first('payment_date') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Assign Member</button>
                            <a href="{{ route('admin.room.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
<script>
    //  SweetAlert2 
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        icon: 'success',
        showConfirmButton: false,
        timer: 3000
    });

    // pass the csrf token for post method.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Initialize Select2
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        $('#form').on("submit", function(event) {
            event.preventDefault();
            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                method: "POST",
                success: function(response) {
                    Toast.fire({
                        type: 'success',
                        title: 'Member assigned to room successfully.'
                    });
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.room.index") }}';
                    }, 1000);
                },
                error: function(xhr) {
                    var errorMsg = 'Something Error Found, Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    Toast.fire({
                        type: 'error',
                        title: errorMsg
                    });
                }
            });
        });
    });
</script>
@endsection

