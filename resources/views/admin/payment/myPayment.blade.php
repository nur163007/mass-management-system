@extends('admin.master')

@section('heading', 'My Payments')
@section('title', 'my-payments')

@section('main-content')
<section class="content">
    <div class="container-fluid">

        <!-- Top Overview Card -->
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6 col-12">
                        <h3 class="card-title font-weight-bolder" style="font-size: 20px;">
                            <i class="fas fa-wallet text-success mr-2"></i> My Payments
                            <span class="text-muted" style="font-size: 14px; font-weight: normal;"> - {{ $member->full_name }}</span>
                        </h3>
                    </div>
                    <div class="col-md-6 col-12 text-md-right mt-2 mt-md-0">
                        <a href="{{ route('admin.add.payment') }}" class="btn btn-sm btn-success mr-1">
                            <i class="fas fa-plus-circle"></i> Add Payment
                        </a>
                        <a href="{{ route('payment.myPayment.pdf', request()->all()) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('admin.includes.message')

                <!-- KPI Summary Cards -->
                <div class="row mb-3">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-info shadow-sm">
                            <div class="inner">
                                <h3>Tk. {{ number_format($totalPaid, 2) }}</h3>
                                <p class="font-weight-bold">Total Paid</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-success shadow-sm">
                            <div class="inner">
                                <h3>Tk. {{ number_format($totalApproved, 2) }}</h3>
                                <p class="font-weight-bold">Approved Payments</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-warning shadow-sm">
                            <div class="inner">
                                <h3 class="text-white">Tk. {{ number_format($totalPending, 2) }}</h3>
                                <p class="font-weight-bold text-white">Pending Approval</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-gradient-secondary shadow-sm">
                            <div class="inner">
                                <h3>{{ count($payments) }}</h3>
                                <p class="font-weight-bold">Total Transactions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Type Breakdown Cards -->
                @if(!empty($typeBreakdown) && count($typeBreakdown) > 0)
                <div class="card card-secondary card-outline mb-4">
                    <div class="card-header py-2">
                        <h4 class="card-title font-weight-bold" style="font-size: 15px;">
                            <i class="fas fa-chart-pie mr-1"></i> Payment Breakdown by Type
                        </h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="row">
                            @php
                                $typeIcons = [
                                    'food_advance' => ['icon' => 'fas fa-utensils', 'color' => 'bg-teal', 'text' => 'text-teal'],
                                    'room_rent' => ['icon' => 'fas fa-home', 'color' => 'bg-primary', 'text' => 'text-primary'],
                                    'room_advance' => ['icon' => 'fas fa-key', 'color' => 'bg-indigo', 'text' => 'text-indigo'],
                                    'electricity' => ['icon' => 'fas fa-bolt', 'color' => 'bg-warning', 'text' => 'text-warning'],
                                    'water' => ['icon' => 'fas fa-tint', 'color' => 'bg-info', 'text' => 'text-info'],
                                    'gas' => ['icon' => 'fas fa-fire', 'color' => 'bg-danger', 'text' => 'text-danger'],
                                    'internet' => ['icon' => 'fas fa-wifi', 'color' => 'bg-purple', 'text' => 'text-purple'],
                                    'bua_moyla' => ['icon' => 'fas fa-broom', 'color' => 'bg-dark', 'text' => 'text-dark'],
                                    'other' => ['icon' => 'fas fa-receipt', 'color' => 'bg-secondary', 'text' => 'text-secondary'],
                                ];
                            @endphp

                            @foreach($typeBreakdown as $typeKey => $info)
                                @php
                                    $cfg = $typeIcons[$typeKey] ?? ['icon' => 'fas fa-money-bill', 'color' => 'bg-secondary', 'text' => 'text-secondary'];
                                    $pct = $totalPaid > 0 ? round(($info['total'] / $totalPaid) * 100, 1) : 0;
                                @endphp
                                <div class="col-md-3 col-sm-6 col-12 mb-2">
                                    <div class="info-box shadow-none border mb-0" style="border-radius: 8px;">
                                        <span class="info-box-icon {{ $cfg['color'] }} text-white elevation-1" style="border-radius: 8px;">
                                            <i class="{{ $cfg['icon'] }}"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text font-weight-bold">{{ $info['label'] }}</span>
                                            <span class="info-box-number text-dark" style="font-size: 16px;">
                                                Tk. {{ number_format($info['total'], 2) }}
                                            </span>
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar {{ $cfg['color'] }}" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="progress-description text-muted" style="font-size: 11px;">
                                                {{ $info['count'] }} payment(s) ({{ $pct }}%)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Filter Form -->
                <div class="card card-light mb-3">
                    <div class="card-body py-2 px-3">
                        <form method="GET" action="{{ route('payment.myPayment') }}" id="myPaymentFilterForm">
                            <div class="row align-items-center">
                                <!-- Month Filter -->
                                <div class="col-md-3 col-sm-6 col-12 mb-2">
                                    <label class="mb-1 font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-calendar-alt text-olive mr-1"></i> Month:
                                    </label>
                                    <select name="month" class="form-control form-control-sm" onchange="document.getElementById('myPaymentFilterForm').submit()">
                                        <option value="">All Months</option>
                                        @foreach($months as $m)
                                            <option value="{{ $m }}" {{ (isset($selectedMonth) && $selectedMonth == $m) ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Payment Type Filter -->
                                <div class="col-md-3 col-sm-6 col-12 mb-2">
                                    <label class="mb-1 font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-tags text-primary mr-1"></i> Payment Type:
                                    </label>
                                    <select name="payment_type" class="form-control form-control-sm" onchange="document.getElementById('myPaymentFilterForm').submit()">
                                        <option value="">All Payment Types</option>
                                        @foreach($paymentTypes as $key => $label)
                                            <option value="{{ $key }}" {{ (isset($selectedType) && $selectedType == $key) ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div class="col-md-3 col-sm-6 col-12 mb-2">
                                    <label class="mb-1 font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-toggle-on text-info mr-1"></i> Status:
                                    </label>
                                    <select name="status" class="form-control form-control-sm" onchange="document.getElementById('myPaymentFilterForm').submit()">
                                        <option value="">All Statuses</option>
                                        <option value="1" {{ (isset($selectedStatus) && $selectedStatus === '1') ? 'selected' : '' }}>Approved Only</option>
                                        <option value="0" {{ (isset($selectedStatus) && $selectedStatus === '0') ? 'selected' : '' }}>Pending Only</option>
                                    </select>
                                </div>

                                <!-- Filter Buttons -->
                                <div class="col-md-3 col-sm-6 col-12 mb-2 d-flex align-items-end" style="padding-top: 24px;">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2 flex-fill">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    @if(!empty($selectedMonth) || !empty($selectedType) || (isset($selectedStatus) && $selectedStatus !== ''))
                                        <a href="{{ route('payment.myPayment') }}" class="btn btn-sm btn-outline-secondary flex-fill" title="Clear all filters">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Active Filters Notification -->
                @if(!empty($selectedMonth) || !empty($selectedType) || (isset($selectedStatus) && $selectedStatus !== ''))
                <div class="mb-3">
                    <span class="text-muted mr-1" style="font-size: 13px;">Active Filters:</span>
                    @if(!empty($selectedMonth))
                        <span class="badge badge-info p-1 mr-1"><i class="fas fa-calendar-alt"></i> Month: {{ $selectedMonth }}</span>
                    @endif
                    @if(!empty($selectedType))
                        <span class="badge badge-primary p-1 mr-1"><i class="fas fa-tag"></i> Type: {{ $paymentTypes[$selectedType] ?? $selectedType }}</span>
                    @endif
                    @if(isset($selectedStatus) && $selectedStatus !== '')
                        <span class="badge {{ $selectedStatus == '1' ? 'badge-success' : 'badge-warning' }} p-1 mr-1">
                            <i class="fas fa-circle"></i> Status: {{ $selectedStatus == '1' ? 'Approved' : 'Pending' }}
                        </span>
                    @endif
                </div>
                @endif

                <!-- Payment Details Table -->
                <div class="table-responsive">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th style="width: 50px; text-align: center;">SL</th>
                                <th style="white-space: nowrap;">Payment Date</th>
                                <th style="white-space: nowrap;">Month</th>
                                <th style="white-space: nowrap;">Payment Type</th>
                                <th style="white-space: nowrap; text-align: right;">Amount</th>
                                <th style="white-space: nowrap; text-align: center;">Status</th>
                                <th>Notes / Remarks</th>
                                <th style="width: 80px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $typeBadges = [
                                    'food_advance' => ['badge' => 'badge-info', 'icon' => 'fas fa-utensils'],
                                    'room_rent' => ['badge' => 'badge-primary', 'icon' => 'fas fa-home'],
                                    'room_advance' => ['badge' => 'badge-indigo', 'icon' => 'fas fa-key'],
                                    'electricity' => ['badge' => 'badge-warning', 'icon' => 'fas fa-bolt'],
                                    'water' => ['badge' => 'badge-info', 'icon' => 'fas fa-tint'],
                                    'gas' => ['badge' => 'badge-danger', 'icon' => 'fas fa-fire'],
                                    'internet' => ['badge' => 'badge-purple', 'icon' => 'fas fa-wifi'],
                                    'bua_moyla' => ['badge' => 'badge-dark', 'icon' => 'fas fa-broom'],
                                    'other' => ['badge' => 'badge-secondary', 'icon' => 'fas fa-receipt'],
                                ];
                            @endphp

                            @forelse($payments as $payment)
                                @php
                                    $pType = $payment->payment_type ?? 'other';
                                    $pLabel = $paymentTypes[$pType] ?? ucfirst(str_replace('_', ' ', $pType));
                                    $badgeCfg = $typeBadges[$pType] ?? ['badge' => 'badge-secondary', 'icon' => 'fas fa-money-bill'];
                                @endphp
                                <tr>
                                    <td style="text-align: center; vertical-align: middle;">{{ $loop->iteration }}</td>
                                    <td style="white-space: nowrap; vertical-align: middle;">
                                        <i class="far fa-calendar-alt text-muted mr-1"></i>
                                        {{ date('d M, Y', strtotime($payment->date)) }}
                                    </td>
                                    <td style="white-space: nowrap; vertical-align: middle;">
                                        <strong>{{ $payment->month }}</strong>
                                    </td>
                                    <td style="white-space: nowrap; vertical-align: middle;">
                                        <span class="badge {{ $badgeCfg['badge'] }} px-2 py-1" style="font-size: 12px; font-weight: 600;">
                                            <i class="{{ $badgeCfg['icon'] }} mr-1"></i> {{ $pLabel }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle; white-space: nowrap;">
                                        <strong class="text-success font-weight-bold" style="font-size: 15px;">
                                            Tk. {{ number_format($payment->payment_amount, 2) }}
                                        </strong>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle; white-space: nowrap;">
                                        @if($payment->status == 1)
                                            <span class="badge badge-success px-2 py-1" style="font-size: 12px;">
                                                <i class="fas fa-check-circle mr-1"></i> Approved
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1" style="font-size: 12px; color: #856404;">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{ $payment->notes ?: '-' }}
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        <button type="button" 
                                                class="btn btn-xs btn-info view-payment-detail-btn"
                                                data-id="{{ $payment->id }}"
                                                data-date="{{ date('d M, Y', strtotime($payment->date)) }}"
                                                data-month="{{ $payment->month }}"
                                                data-type="{{ $pLabel }}"
                                                data-amount="Tk. {{ number_format($payment->payment_amount, 2) }}"
                                                data-status="{{ $payment->status == 1 ? 'Approved' : 'Pending' }}"
                                                data-status-class="{{ $payment->status == 1 ? 'badge-success' : 'badge-warning' }}"
                                                data-notes="{{ $payment->notes ?: 'No additional notes' }}"
                                                data-created="{{ date('d M Y, h:i A', strtotime($payment->created_at)) }}"
                                                title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-2 text-muted"></i>
                                        <p class="font-weight-bold mb-0">No payment records found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($payments) > 0)
                        <tfoot class="bg-light font-weight-bold">
                            <tr>
                                <td colspan="4" class="text-right" style="font-size: 15px;">Grand Total:</td>
                                <td class="text-right text-success" style="font-size: 16px;">
                                    <strong>Tk. {{ number_format($totalPaid, 2) }}</strong>
                                </td>
                                <td colspan="3">
                                    <span class="text-muted" style="font-size: 12px;">
                                        (Approved: Tk. {{ number_format($totalApproved, 2) }} | Pending: Tk. {{ number_format($totalPending, 2) }})
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- Payment Detail Modal -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" role="dialog" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-olive text-white">
                <h5 class="modal-title font-weight-bold" id="paymentDetailModalLabel">
                    <i class="fas fa-receipt mr-1"></i> Payment Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 40%;"><i class="fas fa-user text-muted mr-1"></i> Member:</th>
                            <td class="font-weight-bold">{{ $member->full_name }}</td>
                        </tr>
                        <tr>
                            <th><i class="far fa-calendar-alt text-muted mr-1"></i> Payment Date:</th>
                            <td id="modalDate">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar text-muted mr-1"></i> Month:</th>
                            <td id="modalMonth" class="font-weight-bold">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-tags text-muted mr-1"></i> Payment Type:</th>
                            <td id="modalType" class="font-weight-bold">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-money-bill-wave text-muted mr-1"></i> Amount:</th>
                            <td id="modalAmount" class="font-weight-bold text-success" style="font-size: 16px;">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-info-circle text-muted mr-1"></i> Status:</th>
                            <td><span id="modalStatus" class="badge px-2 py-1">-</span></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock text-muted mr-1"></i> Recorded At:</th>
                            <td id="modalCreated" class="text-muted">-</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-sticky-note text-muted mr-1"></i> Notes:</th>
                            <td id="modalNotes" style="white-space: pre-line;">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(document).ready(function() {
        if ($("#all-category tbody tr").length > 0 && !$("#all-category tbody tr td").hasClass("text-center")) {
            $("#all-category").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[1, "desc"]],
                "pageLength": 25,
                "language": {
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search payments..."
                }
            });
        }

        // View Detail Modal Handler
        $(document).on('click', '.view-payment-detail-btn', function() {
            var btn = $(this);
            $('#modalDate').text(btn.data('date'));
            $('#modalMonth').text(btn.data('month'));
            $('#modalType').text(btn.data('type'));
            $('#modalAmount').text(btn.data('amount'));
            $('#modalCreated').text(btn.data('created'));
            $('#modalNotes').text(btn.data('notes'));
            
            var statusBadge = $('#modalStatus');
            statusBadge.text(btn.data('status'));
            statusBadge.removeClass('badge-success badge-warning');
            statusBadge.addClass(btn.data('status-class'));

            $('#paymentDetailModal').modal('show');
        });
    });
</script>
@endsection
