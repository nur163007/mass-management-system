@extends('admin.master')

@section('heading', 'Service Charge')
@section('title', 'service charge')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Service Charge Management</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.servicecharge.expenses')}}" class="viewall"><i class="fas fa-list"></i> View Expenses</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Service Charge Information</span>
                                    <span class="info-box-number">New Member Service Charge: Tk. 1,000 (Non-refundable)</span>
                                    <p class="text-sm text-white mt-2 mb-0">
                                        Service charge is collected from new members and used for house maintenance and other expenses. 
                                        All expenses from service charge are tracked separately.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Collected</span>
                                    <span class="info-box-number">Tk. {{ number_format($totalCollected, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Spent</span>
                                    <span class="info-box-number">Tk. {{ number_format($totalSpent, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Balance</span>
                                    <span class="info-box-number">Tk. {{ number_format($balance, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-primary">
                            <h3 class="card-title font-weight-bold">Add Service Charge Payment</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.servicecharge.store') }}" method="POST" id="form">
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
                                        <label for="amount">Service Charge Amount (Tk.)</label>
                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter service charge amount" value="1000" step="0.01" min="0" required autocomplete="off">
                                        @if ($errors->has('amount'))
                                            <p class="text-danger">{{ $errors->first('amount') }}</p>
                                        @endif
                                        <small class="text-muted">Default: Tk. 1,000 (Non-refundable)</small>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="payment_date">Payment Date</label>
                                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required autocomplete="off">
                                        @if ($errors->has('payment_date'))
                                            <p class="text-danger">{{ $errors->first('payment_date') }}</p>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="notes">Notes (Optional)</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Add any notes about this payment" autocomplete="off"></textarea>
                                        @if ($errors->has('notes'))
                                            <p class="text-danger">{{ $errors->first('notes') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Add Service Charge</button>
                                    <a href="{{ route('admin.servicecharge.expenses') }}" class="btn btn-secondary">View Expenses</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-info">
                            <h3 class="card-title font-weight-bold">Service Charge History</h3>
                        </div>
                        <div class="card-body">
                            @if($serviceCharges->count() > 0)
                            <table class="table table-bordered table-hover">
                                <thead class="bg-cyan">
                                    <tr>
                                        <th>SL NO</th>
                                        <th>Member Name</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceCharges as $index => $charge)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $charge->member->full_name ?? 'N/A' }}
                                            @if($charge->member && $charge->member->role_id == 2)
                                                <span class="badge badge-warning">Manager</span>
                                            @endif
                                        </td>
                                        <td>Tk. {{ number_format($charge->amount, 2) }}</td>
                                        <td>{{ $charge->payment_date ? date('d M Y', strtotime($charge->payment_date)) : 'N/A' }}</td>
                                        <td>{{ $charge->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="alert alert-info text-center">
                                <h5>No service charges recorded yet</h5>
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

        // Initialize DataTable for service charge history
        $('table').DataTable();

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
                        title: 'Service charge added successfully.'
                    });
                    setTimeout(function() {
                        location.reload();
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

