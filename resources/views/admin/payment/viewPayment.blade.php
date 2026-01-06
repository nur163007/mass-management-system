@extends('admin.master')

@section('heading', 'All Payments')
@section('title', 'view payment')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Payments</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.payment')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                         <a href="{{route('admin.payment.downloadPdf')}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>
 
                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.view.payment') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold"><i class="fas fa-filter"></i> Filter by Member</label>
                                            <select name="member_id" class="form-control" onchange="document.getElementById('filterForm').submit()" style="border-radius: 5px;">
                                                <option value="">All Members</option>
                                                @foreach($members as $member)
                                                    <option value="{{ $member->id }}" {{ $selectedMemberId == $member->id ? 'selected' : '' }}>
                                                        {{ $member->full_name }}
                                                        @if ($member->role_id == 2)
                                                            (Manager)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold"><i class="fas fa-filter"></i> Filter by Payment Type</label>
                                            <select name="payment_type" class="form-control" onchange="document.getElementById('filterForm').submit()" style="border-radius: 5px;">
                                                <option value="">All Payment Types</option>
                                                @foreach($paymentTypes as $key => $label)
                                                    <option value="{{ $key }}" {{ $selectedPaymentType == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th style="white-space: nowrap;">SL NO</th>
                                <th style="white-space: nowrap;">Member's Name</th>
                                <th style="white-space: nowrap;">Payment Type</th>
                                <th style="white-space: nowrap;">Payment Amount</th>
                                <th style="white-space: nowrap;">Date</th>
                                <th style="white-space: nowrap;">Status</th>
                                <th style="white-space: nowrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                 
                            {{-- show data using ajax --}}
                        @foreach ($payments as $payment)
                            <tr>
                                <td style="white-space: nowrap;">{{ $loop->iteration }}</td>
                                <td style="white-space: nowrap;">{{ $payment->full_name }}</td>
                                <td style="white-space: nowrap;">
                                    @php
                                        $paymentTypes = [
                                            'food_advance' => 'Food Advance',
                                            'room_rent' => 'Room Rent',
                                            'room_advance' => 'Room Advance',
                                            'water' => 'Water Bill',
                                            'internet' => 'Internet Bill',
                                            'electricity' => 'Electricity Bill',
                                            'gas' => 'Gas Bill',
                                            'bua_moyla' => 'Bua & Moyla Bill',
                                        ];
                                        $typeName = $paymentTypes[$payment->payment_type] ?? ucfirst(str_replace('_', ' ', $payment->payment_type));
                                    @endphp
                                    {{ $typeName }}
                                </td>
                                <td style="white-space: nowrap;">Tk. {{ number_format($payment->payment_amount, 2) }}</td>
                                <td style="white-space: nowrap;">{{ date('M d, Y', strtotime($payment->date ))}}</td>
                                <td style="white-space: nowrap;">
                                    @if($payment->status == 1)
                                        <span class="badge badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td style="width: 180px; white-space: nowrap;">
                                    <a href="{{route('admin.view.payment.details', $payment->id)}}" class="btn btn-info btn-xs" title="View" style="border-radius: 5px; padding: 5px 10px;"> 
                                        <i class="fa fa-eye"></i> 
                                    </a>
                                    <a href="{{route('admin.edit.payment',$payment->id)}}" class="btn btn-warning btn-xs" title="Edit" style="border-radius: 5px; padding: 5px 10px; margin-left: 5px;"> 
                                        <i class="fas fa-pencil-alt"></i> 
                                    </a>
                                    @if($payment->status == 0)
                                        <button type="button" class="btn btn-success btn-xs approve-payment-btn" data-id="{{ $payment->id }}" title="Approve" style="border-radius: 5px; padding: 5px 10px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2); cursor: pointer; margin-left: 5px;">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

        $(document).ready(function(){
            $("#all-category").DataTable();

            // Handle approve button click
            $(document).on('click', '.approve-payment-btn', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var btn = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to approve this payment?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed || result.value === true) {
                        $.ajax({
                            url: "{{ url('admin/payment/paymentStatus') }}/" + id + '/1',
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response && response.message == 'Success') {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Payment successfully approved.',
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    Toast.fire({
                                        type: 'error',
                                        title: (response && response.message) ? response.message : 'Something Error Found, Please try again.',
                                    });
                                }
                            },
                            error: function(xhr) {
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something Error Found, Please try again.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
