@extends('admin.master')

@section('heading', 'User Payment')
@section('title', 'user-payment')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">View Payment</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('user.addPayment')}}" class="viewall bg-cyan"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                      <a href="{{route('user.pendingPayment')}}" class="viewall bg-olive"><i class="fas fa-parking"></i> Pending Payment</a>
                </div>
            </div>
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Month</th>
                                <th>Total Payment Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @php
                                $monthMap = [
                                    'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                    'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                    'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                ];
                            @endphp
                            {{-- show data using ajax --}}
                        
                     @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $monthMap[$payment->month] ?? $payment->month }}</strong></td>
                                <td><strong>Tk. {{ number_format($payment->total_amount, 2) }}</strong></td>
           
                                <td style="width: 120px">
                                    <button type="button" class="btn btn-info btn-xs view-payment-details" data-month="{{ $monthMap[$payment->month] ?? $payment->month }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">Payment Details - <span id="modalMonth"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Payment Type</th>
                                    <th>Payment Date</th>
                                    <th class="text-right">Amount</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="paymentDetailsBody">
                                <tr>
                                    <td colspan="5" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right">Total:</td>
                                    <td class="text-right" id="paymentTotal">Tk. 0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        var base_url = window.location.origin;
            //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });

     
        $(function() {
            $("#all-category").DataTable();
        });

        // Handle view payment details button click
        $(document).on('click', '.view-payment-details', function() {
            var month = $(this).data('month');
            $('#modalMonth').text(month);
            $('#paymentDetailsModal').modal('show');
            
            // Load payment details via AJAX
            var url = "{{ url('user/payment/details') }}/" + month;
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        var html = '';
                        if (response.payments && response.payments.length > 0) {
                            response.payments.forEach(function(payment, index) {
                                html += '<tr>';
                                html += '<td>' + (index + 1) + '</td>';
                                html += '<td><strong>' + payment.type + '</strong></td>';
                                html += '<td><span class="badge badge-secondary">' + payment.date + '</span></td>';
                                html += '<td class="text-right"><strong class="text-success">Tk. ' + parseFloat(payment.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></td>';
                                html += '<td>' + (payment.notes ? payment.notes : '-') + '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html = '<tr><td colspan="5" class="text-center">No payments found for this month.</td></tr>';
                        }
                        $('#paymentDetailsBody').html(html);
                        $('#paymentTotal').text('Tk. ' + parseFloat(response.total).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    } else {
                        $('#paymentDetailsBody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load payment details.</td></tr>');
                    }
                },
                error: function() {
                    $('#paymentDetailsBody').html('<tr><td colspan="5" class="text-center text-danger">Error loading payment details. Please try again.</td></tr>');
                    Toast.fire({
                        type: 'error',
                        title: 'Failed to load payment details.'
                    });
                }
            });
        });

    </script>
@endsection
