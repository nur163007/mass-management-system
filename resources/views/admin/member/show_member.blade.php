@extends('admin.master')

@section('heading', 'Member Details')
@section('title', $member->full_name . ' | MMS')

@section('custom_css')
    <style>
        :root {
            --md-radius: 16px;
            --md-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
            border-radius: var(--md-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.22);
        }

        .md-hero::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.28) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .md-hero-btn {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: var(--md-transition);
        }

        .md-hero-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .md-panel {
            background: #ffffff;
            border-radius: var(--md-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .md-panel-head {
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .md-panel-title {
            font-size: 0.98rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .md-avatar-lg {
            width: 110px;
            height: 110px;
            border-radius: 18px;
            object-fit: cover;
            border: 3px solid #e2e8f0;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.1);
            background: #f1f5f9;
        }

        .md-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .md-info-row:last-child {
            border-bottom: none;
        }

        .md-nid-img {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .md-mini-kpi {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            height: 100%;
        }

        .md-mini-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .md-mini-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }
    </style>
@endsection

@section('main-content')
    @php
        $paymentTypeLabels = [
            'food_advance' => 'Food Advance',
            'meal_expanse_payment' => 'Meal Payment',
            'meal_payment' => 'Meal Payment',
            'room_rent' => 'Room Rent',
            'room_advance' => 'Room Advance',
            'water' => 'Water Bill',
            'internet' => 'Internet Bill',
            'electricity' => 'Electricity Bill',
            'gas' => 'Gas Bill',
            'bua_moyla' => 'Bua & Moyla Bill',
        ];
    @endphp

    <section class="content">
        <div class="container-fluid">

            <!-- Hero Header -->
            <div class="md-hero">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h3 class="mb-1 font-weight-bold" style="font-size: 1.45rem;">
                            <i class="fas fa-user-circle mr-2 text-info"></i> {{ $member->full_name }}
                        </h3>
                        <p class="mb-0" style="color: #94a3b8; font-size: 0.88rem;">
                            Member Profile, Documents, Monthly Balance & Recent Payment History
                        </p>
                    </div>
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        <a href="{{ route('admin.view-member') }}" class="md-hero-btn">
                            <i class="fas fa-arrow-left"></i> Back to Members
                        </a>
                        @if (session('role') == 1)
                            <a href="{{ route('admin.edit-member', $member->id) }}" class="md-hero-btn"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: transparent;">
                                <i class="fas fa-pencil-alt"></i> Edit Member
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Profile & NID -->
                <div class="col-lg-4 col-12">
                    <div class="md-panel">
                        <div class="p-4 text-center border-bottom bg-light">
                            @php
                                $defaultAvatar = asset('assets/admin/dist/img/avatar5.png');
                                $photoSrc = ($member->photo && $member->photo !== 'null')
                                    ? asset('uploads/members/profile/' . $member->photo)
                                    : $defaultAvatar;
                            @endphp
                            <img class="md-avatar-lg mb-3"
                                src="{{ $photoSrc }}"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatar }}'"
                                alt="{{ $member->full_name }}">
                            <h5 class="font-weight-bold text-dark mb-1">{{ $member->full_name }}</h5>
                            <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                <span class="badge {{ $member->role_id == 2 ? 'badge-warning' : 'badge-info' }} px-3 py-1"
                                    style="border-radius: 50px;">
                                    {{ $member->role_name ?? ($member->role_id == 2 ? 'Manager' : 'User') }}
                                </span>
                                @if ($member->status == 1)
                                    <span class="badge badge-success px-3 py-1" style="border-radius: 50px;">Active</span>
                                @else
                                    <span class="badge badge-secondary px-3 py-1" style="border-radius: 50px;">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="md-info-row">
                                <span class="text-muted font-weight-bold"><i class="fas fa-phone-alt mr-2 text-success"></i>Phone</span>
                                <strong class="text-dark">0{{ $member->phone_no }}</strong>
                            </div>
                            <div class="md-info-row">
                                <span class="text-muted font-weight-bold"><i class="fas fa-envelope mr-2 text-info"></i>Email</span>
                                <strong class="text-dark">{{ $member->email ?? 'N/A' }}</strong>
                            </div>
                            <div class="md-info-row">
                                <span class="text-muted font-weight-bold"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>Address</span>
                                <strong class="text-dark text-right" style="max-width: 60%;">{{ $member->address ?? 'N/A' }}</strong>
                            </div>
                            <div class="md-info-row">
                                <span class="text-muted font-weight-bold"><i class="fas fa-door-open mr-2 text-primary"></i>Room</span>
                                <strong class="text-dark">{{ $balance['room_name'] ?? 'Not Assigned' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- NID Document Card -->
                    <div class="md-panel">
                        <div class="md-panel-head">
                            <h6 class="md-panel-title">
                                <i class="fas fa-id-card text-primary mr-2"></i> NID Document
                            </h6>
                        </div>
                        <div class="p-3 text-center">
                            @if (!empty($member->nid_photo) && $member->nid_photo !== 'null')
                                <img class="md-nid-img"
                                    src="{{ asset('uploads/members/nid/' . $member->nid_photo) }}"
                                    onerror="this.style.display='none'; document.getElementById('noNidMsg').style.display='block';"
                                    alt="NID Photo">
                                <div id="noNidMsg" class="text-muted py-4" style="display: none;">
                                    <i class="far fa-image fa-2x mb-2 d-block"></i> NID photo unavailable
                                </div>
                            @else
                                <div class="text-muted py-4">
                                    <i class="far fa-image fa-2x mb-2 d-block"></i> No NID photo uploaded
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Monthly Standing & Recent Payments -->
                <div class="col-lg-8 col-12">
                    @if ($balance)
                        <div class="md-panel">
                            <div class="md-panel-head">
                                <h6 class="md-panel-title">
                                    <i class="fas fa-chart-pie text-success mr-2"></i>
                                    {{ $currentMonth }} Financial Standing
                                </h6>
                                <span class="badge {{ ($balance['balance'] ?? 0) < 0 ? 'badge-danger' : 'badge-success' }} px-3 py-1"
                                    style="border-radius: 50px;">
                                    {{ ($balance['balance'] ?? 0) < 0 ? 'Due' : 'Clear / Advance' }}
                                </span>
                            </div>
                            <div class="p-4">
                                <div class="row">
                                    <div class="col-md-4 col-6 mb-3">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Room Rent</div>
                                            <div class="md-mini-val">Tk. {{ number_format($balance['room_rent'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-3">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Utility Bills</div>
                                            <div class="md-mini-val">Tk. {{ number_format($balance['bills_total'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-3">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Meals ({{ $balance['meal_count'] ?? 0 }})</div>
                                            <div class="md-mini-val">Tk. {{ number_format($balance['meal_cost'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-3 mb-md-0">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Total Cost</div>
                                            <div class="md-mini-val text-dark">Tk. {{ number_format($balance['total_cost'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-6 mb-3 mb-md-0">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Total Paid</div>
                                            <div class="md-mini-val text-success">Tk. {{ number_format($balance['total_payment'] ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="md-mini-kpi">
                                            <div class="md-mini-label">Net Balance</div>
                                            @php $bal = $balance['balance'] ?? 0; @endphp
                                            <div class="md-mini-val {{ $bal < 0 ? 'text-danger' : 'text-success' }}">
                                                Tk. {{ number_format(abs($bal), 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Payments -->
                    <div class="md-panel">
                        <div class="md-panel-head">
                            <h6 class="md-panel-title">
                                <i class="fas fa-history text-info mr-2"></i> Recent Payments
                            </h6>
                            <a href="{{ route('admin.view.payment', ['member_id' => $member->id]) }}"
                                class="btn btn-sm btn-outline-primary font-weight-bold" style="border-radius: 8px;">
                                View All Payments
                            </a>
                        </div>
                        <div class="p-3">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Month</th>
                                            <th>Type</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->date ? date('d M Y', strtotime($payment->date)) : '-' }}</td>
                                                <td><span class="badge badge-light border">{{ $payment->month }}</span></td>
                                                <td class="font-weight-bold">
                                                    {{ $paymentTypeLabels[$payment->payment_type] ?? ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                                </td>
                                                <td class="text-right font-weight-bold text-success">
                                                    Tk. {{ number_format($payment->payment_amount, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($payment->status == 1)
                                                        <span class="badge badge-success px-2 py-1">Paid</span>
                                                    @else
                                                        <span class="badge badge-warning px-2 py-1">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    No payment records found for this member.
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

        </div>
    </section>
@endsection
