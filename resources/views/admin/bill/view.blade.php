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
                        <a href="{{route('admin.bill.index')}}" class="viewall"><i class="fas fa-list"></i> All Bills</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <!-- Month Filter (Moved to Top) -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Filter by Month</h3>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('admin.bill.view', $bill->id) }}" id="monthFilterForm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="month_filter">Select Month:</label>
                                                <select class="form-control" id="month_filter" name="month" onchange="document.getElementById('monthFilterForm').submit();">
                                                    @php
                                                        $allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                        $currentMonth = \Carbon\Carbon::now()->format('F');
                                                    @endphp
                                                    @foreach($allMonths as $month)
                                                        <option value="{{ $month }}" {{ $selectedMonthFull == $month ? 'selected' : '' }}>
                                                            {{ $month }}
                                                            @if($month == $currentMonth)
                                                                (Current)
                                                            @endif
                                                            @if($month == $billMonthFull)
                                                                (Bill Month)
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="alert alert-info mt-4 mb-0">
                                                    <i class="fas fa-info-circle"></i> 
                                                    <strong>Showing data for:</strong> 
                                                    <span class="badge badge-primary">{{ $selectedMonthFull }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bill Information Card -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Bill Information ({{ $selectedMonthFull }})</h3>
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
                                            <span class="font-weight-bold" style="font-size: 16px;">{{ $selectedMonthFull }}</span>
                                        </div>
                                    </div>
                                    @if($bill->notes)
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <strong>Notes:</strong><br>
                                            <span>{{ $bill->notes }}</span>
                                        </div>
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
                                    <p>Members Paid ({{ $selectedMonthFull }})</p>
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
                                    <p>Total Payments ({{ $selectedMonthFull }})</p>
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
                                    <p>Total Paid Amount ({{ $selectedMonthFull }})</p>
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
                                    <p>Due Amount</p>
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
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Payment Details by Member 
                                <span class="badge badge-primary">({{ $selectedMonthFull }})</span>
                            </h3>
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
                                        <th>Payment Details</th>
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
                                            @if($memberData['total_paid'] > 0)
                                                <span class="badge badge-success" style="font-size: 14px;">
                                                    Tk. {{ number_format($memberData['total_paid'], 2) }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger" style="font-size: 14px;">
                                                    Tk. 0.00
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($memberData['payment_count'] > 0)
                                                <span class="badge badge-info">{{ $memberData['payment_count'] }} time(s)</span>
                                            @else
                                                <span class="badge badge-warning">0 time(s)</span>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse($memberData['payments'] as $payment)
                                                @php
                                                    $monthMap = [
                                                        'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                                        'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                                        'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                                    ];
                                                    $paymentMonth = $monthMap[$payment->month] ?? $payment->month;
                                                @endphp
                                                <div class="mb-2 p-2 border rounded">
                                                    <strong>Date:</strong> {{ date('d M Y', strtotime($payment->date)) }}<br>
                                                    <strong>Amount:</strong> <span class="text-success font-weight-bold">Tk. {{ number_format($payment->payment_amount, 2) }}</span><br>
                                                    <strong>Month:</strong> {{ $paymentMonth }}
                                                </div>
                                            @empty
                                                <span class="text-muted">No payment yet</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i> No members found for this bill.
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Total:</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-success" style="font-size: 14px;">
                                                Tk. {{ number_format($paymentsByMember->sum('total_paid'), 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ $paymentsByMember->sum('payment_count') }} time(s)
                                            </span>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Total Members:</strong>
                                        </td>
                                        <td colspan="4">
                                            <span class="badge badge-primary">
                                                {{ $paymentsByMember->count() }} Members
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Members Paid:</strong>
                                        </td>
                                        <td colspan="4">
                                            <span class="badge badge-success">
                                                {{ $paymentsByMember->filter(function($m) { return $m['total_paid'] > 0; })->count() }} Members
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <strong>Members Not Paid:</strong>
                                        </td>
                                        <td colspan="4">
                                            <span class="badge badge-danger">
                                                {{ $paymentsByMember->filter(function($m) { return $m['total_paid'] == 0; })->count() }} Members
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
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
        // DataTable disabled - using plain table to avoid DOM manipulation issues
        // All data will be displayed in the table without pagination
    });
</script>
@endsection

