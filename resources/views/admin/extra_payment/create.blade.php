@extends('admin.master')

@section('heading', 'Add Extra Payment')
@section('title', 'add extra payment')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Add Extra Payment</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.extra_payment.index')}}" class="viewall"><i class="fas fa-list"></i> All Extra Payments</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <form action="{{ route('admin.extra_payment.store') }}" method="POST" id="form">
                        @csrf
                        <div class="row">
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
                                <label for="amount">Payment Amount (Tk.)</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter payment amount" step="0.01" min="0" required autocomplete="off">
                                @if ($errors->has('amount'))
                                    <p class="text-danger">{{ $errors->first('amount') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="rent_reduction">Rent Reduction Amount (Tk.)</label>
                                <input type="number" class="form-control" id="rent_reduction" name="rent_reduction" placeholder="Enter rent reduction amount" step="0.01" min="0" required autocomplete="off">
                                @if ($errors->has('rent_reduction'))
                                    <p class="text-danger">{{ $errors->first('rent_reduction') }}</p>
                                @endif
                                <small class="text-muted">Amount to be reduced from house rent</small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required autocomplete="off">
                                @if ($errors->has('payment_date'))
                                    <p class="text-danger">{{ $errors->first('payment_date') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="month">Month</label>
                                <select class="form-control" id="month" name="month" required autocomplete="off">
                                    <option value="">--Select Month--</option>
                                    @php
                                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                        $currentMonth = \Carbon\Carbon::now()->isoFormat('MMM');
                                    @endphp
                                    @foreach($months as $m)
                                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('month'))
                                    <p class="text-danger">{{ $errors->first('month') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label for="description">Description (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add description about this payment" autocomplete="off"></textarea>
                                @if ($errors->has('description'))
                                    <p class="text-danger">{{ $errors->first('description') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Add Extra Payment</button>
                            <a href="{{ route('admin.extra_payment.index') }}" class="btn btn-secondary">Cancel</a>
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
                        title: 'Extra payment added successfully.'
                    });
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.extra_payment.index") }}';
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

