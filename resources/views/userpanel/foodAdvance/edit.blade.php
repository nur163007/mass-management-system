@extends('admin.master')

@section('heading', 'Edit Food Advance')
@section('title', 'edit-food-advance')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Edit Food Advance</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.foodAdvance.index')}}" class="viewall"><i class="fas fa-list"></i> Food Advance List</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <form method="POST" id="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $advance->id }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" id="amount" name="amount" step="0.01" min="1" placeholder="Enter Amount" value="{{ $advance->amount }}" required>
                                @if ($errors->has('amount'))
                                    <p class="text-danger">{{ $errors->first('amount') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="date" name="date" value="{{ $advance->date ? \Carbon\Carbon::parse($advance->date)->format('Y-m-d') : '' }}" required>
                                @if ($errors->has('date'))
                                    <p class="text-danger">{{ $errors->first('date') }}</p>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter notes">{{ $advance->notes }}</textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
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
            showConfirmbutton: false,
            timer: 3000
        });

        // pass the csrf token for post method.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    url: "{{ route('user.foodAdvance.update') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            Toast.fire({
                                type: 'success',
                                title: 'Food advance successfully updated.',
                            });
                            // Redirect to food advance list after 1.5 seconds
                            setTimeout(function() {
                                window.location.href = "{{ route('user.foodAdvance.index') }}";
                            }, 1500);
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: 'Something Error Found, Please try again.',
                            });
                        }
                    },
                    error: function(error) {
                        Toast.fire({
                            type: 'error',
                            title: 'Something Error Found, Please try again.',
                        });
                    }
                });
            });
        });
    </script>
@endsection

