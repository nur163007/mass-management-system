@extends('admin.master')

@section('heading', 'Bill Details')
@section('title', 'bill-details')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Bill Payment Details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.bill.index')}}" class="viewall"><i class="fas fa-list"></i> All Bills</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <!-- Bill Information Card -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Bill Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Bill Type:</strong><br>
                                            @if($bill->bill_type == 'water')
                                                <span class="badge badge-primary badge-lg">Water Bill</span>
                                            @elseif($bill->bill_type == 'internet')
                                                <span class="badge badge-info badge-lg">Internet Bill</span>
                                            @elseif($bill->bill_type == 'electricity')
                                                <span class="badge badge-warning badge-lg">Electricity Bill</span>
                                            @elseif($bill->bill_type == 'gas')
                                                <span class="badge badge-danger badge-lg">Gas Bill</span>
                                            @elseif($bill->bill_type == 'bua_moyla')
                                                <span class="badge badge-secondary badge-lg">Bua & Moyla Bill</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Total Amount:</strong><br>
                                            <span class="text-success font-weight-bold" style="font-size: 18px;">Tk. {{ number_format($bill->total_amount, 2) }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Per Person Amount:</strong><br>
                                            <span class="text-info font-weight-bold" style="font-size: 18px;">Tk. {{ number_format($bill->per_person_amount, 2) }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Month:</strong><br>
                                            <span class="font-weight-bold" style="font-size: 16px;">
                                                @php
                                                    $monthMap = [
                                                        'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                                        'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                                        'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                                    ];
                                                    echo $monthMap[$bill->month] ?? $bill->month;
                                                @endphp
                                            </span>
                                        </div>
                                    </div>
                                    @if($bill->bill_date)
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <strong>Bill Date:</strong><br>
                                            <span>{{ date('d M Y', strtotime($bill->bill_date)) }}</span>
                                        </div>
                                        @if($bill->notes)
                                        <div class="col-md-9">
                                            <strong>Notes:</strong><br>
                                            <span>{{ $bill->notes }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $uniquePaidMembers }}</h3>
                                    <p>Members Paid</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalPaidCount }}</h3>
                                    <p>Total Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Tk. {{ number_format($totalPaid, 2) }}</h3>
                                    <p>Total Paid Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Tk. {{ number_format($bill->total_amount - $totalPaid, 2) }}</h3>
                                    <p>Remaining Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details Table -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list"></i> Payment Details by Member</h3>
                        </div>
                        <div class="card-body">
                            <table id="payment-table" class="table table-bordered table-hover">
                                <thead class="bg-success">
                                    <tr>
                                        <th>SL NO</th>
                                        <th>Member Name</th>
                                        <th>Phone</th>
                                        <th>Total Paid</th>
                                        <th>Payment Count</th>
                                        <th>Payment Dates</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paymentsByMember as $memberId => $memberData)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $memberData['member_name'] }}</strong>
                                        </td>
                                        <td>{{ $memberData['phone_no'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-success" style="font-size: 14px;">
                                                Tk. {{ number_format($memberData['total_paid'], 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $memberData['payment_count'] }} time(s)</span>
                                        </td>
                                        <td>
                                            @foreach($memberData['payments'] as $payment)
                                                <span class="badge badge-secondary">{{ date('d M Y', strtotime($payment->date)) }}</span><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#paymentModal{{ $memberId }}">
                                                <i class="fas fa-eye"></i> Details
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Payment Details Modal -->
                                    <div class="modal fade" id="paymentModal{{ $memberId }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user"></i> Payment Details - {{ $memberData['member_name'] }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th>SL</th>
                                                                <th>Payment Date</th>
                                                                <th>Amount</th>
                                                                <th>Month</th>
                                                                @if($memberData['payments']->first()->notes)
                                                                <th>Notes</th>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($memberData['payments'] as $index => $payment)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ date('d M Y', strtotime($payment->date)) }}</td>
                                                                <td><strong class="text-success">Tk. {{ number_format($payment->payment_amount, 2) }}</strong></td>
                                                                <td>
                                                                    @php
                                                                        $monthMap = [
                                                                            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                                                            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                                                            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                                                        ];
                                                                        echo $monthMap[$payment->month] ?? $payment->month;
                                                                    @endphp
                                                                </td>
                                                                @if($payment->notes)
                                                                <td>{{ $payment->notes }}</td>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                            <tr class="bg-light font-weight-bold">
                                                                <td colspan="2" class="text-right">Total:</td>
                                                                <td class="text-success">Tk. {{ number_format($memberData['total_paid'], 2) }}</td>
                                                                <td colspan="{{ $memberData['payments']->first()->notes ? '2' : '1' }}"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i> No payments found for this bill.
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
        $("#payment-table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "order": [[0, "asc"]]
        });
    });
</script>
@endsection

