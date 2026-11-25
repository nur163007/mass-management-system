@extends('admin.master')

@section('heading', 'Dashboard')
@section('title', 'Dashboard | MMS')

@section('custom_css')
    <style>
        /* Modern Dashboard Root Styling */
        :root {
            --card-radius: 16px;
            --transition-fast: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hero Banner */
        .dash-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            border-radius: var(--card-radius);
            color: #ffffff;
            padding: 24px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
        }

        .dash-hero::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .dash-hero .hero-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .dash-hero .hero-subtitle {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 0;
        }

        .dash-hero .month-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            color: #ffffff;
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .dash-hero .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .dash-hero .quick-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition-fast);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .dash-hero .quick-btn:hover {
            background: #ffffff;
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Modern Metric Cards */
        .m-card {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid #f1f5f9;
            padding: 20px;
            position: relative;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            transition: var(--transition-fast);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .m-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.09);
            border-color: #e2e8f0;
        }

        .m-card .m-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .m-card .m-title {
            font-size: 0.78rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .m-card .m-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }

        .m-card .m-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .m-card .m-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
        }

        .m-card .m-footer-badge {
            background: #f8fafc;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.72rem;
            border: 1px solid #e2e8f0;
        }

        .m-card .m-footer-link {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: var(--transition-fast);
        }

        .m-card .m-footer-link:hover {
            color: #1d4ed8;
            transform: translateX(3px);
        }

        /* Card Gradient Palettes */
        .theme-indigo .m-icon-wrap {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .theme-emerald .m-icon-wrap {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .theme-amber .m-icon-wrap {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .theme-blue .m-icon-wrap {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
        }

        .theme-rose .m-icon-wrap {
            background: linear-gradient(135deg, #f43f5e, #e11d48);
        }

        .theme-crimson .m-icon-wrap {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .theme-slate .m-icon-wrap {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .theme-purple .m-icon-wrap {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        /* Financial Health Panel */
        .fin-panel {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .fin-panel .panel-head {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .fin-panel .panel-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fin-panel .panel-body {
            padding: 22px;
        }

        .fin-stat-box {
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 16px;
            text-align: center;
            height: 100%;
        }

        .fin-stat-box .num {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .fin-stat-box .lbl {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: #64748b;
            margin: 0;
        }

        /* Custom Modern Progress */
        .m-progress-wrap {
            margin: 18px 0;
        }

        .m-progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .m-progress-bar {
            height: 10px;
            background: #e2e8f0;
            border-radius: 50px;
            overflow: hidden;
        }

        .m-progress-fill {
            height: 100%;
            border-radius: 50px;
            transition: width 0.6s ease;
        }

        /* Member Table Styling */
        .m-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .m-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }

        .m-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.86rem;
            color: #1e293b;
        }

        .m-table tbody tr:hover {
            background: #f8fafc;
        }

        .m-badge {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.74rem;
            font-weight: 600;
            display: inline-block;
        }

        .m-badge-due {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .m-badge-credit {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .m-badge-zero {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        /* Responsive tweaks */
        @media (max-width: 767px) {
            .dash-hero {
                padding: 18px;
            }

            .dash-hero .hero-title {
                font-size: 1.25rem;
            }

            .dash-hero .month-pill {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }

            .dash-hero .quick-actions {
                width: 100%;
            }

            .dash-hero .quick-btn {
                flex: 1 1 45%;
                justify-content: center;
            }

            .m-card .m-value {
                font-size: 1.3rem;
            }

            .m-card .m-icon-wrap {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }
        }
    </style>
@endsection

@section('main-content')
    <section class="content pt-2 pb-4">
        <div class="container-fluid">

            <!-- Modern Hero Banner -->
            <div class="dash-hero">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-12">
                        <h2 class="hero-title">
                            <i class="fas fa-home text-warning mr-2"></i>Mess Management Dashboard
                        </h2>
                        <p class="hero-subtitle">
                            Real-time mess accounts, meals, and member financial overview.
                        </p>
                        <div class="quick-actions">
                            <a href="{{ route('admin.add.meal') }}" class="quick-btn">
                                <i class="fas fa-plus text-success"></i> Add Meal
                            </a>
                            <a href="{{ route('admin.add.expanse') }}" class="quick-btn">
                                <i class="fas fa-shopping-basket text-danger"></i> Add Bazar
                            </a>
                            <a href="{{ route('admin.add.payment') }}" class="quick-btn">
                                <i class="fas fa-wallet text-info"></i> Add Payment
                            </a>
                            <a href="{{ route('admin.foodAdvance.create') }}" class="quick-btn">
                                <i class="fas fa-hand-holding-usd text-warning"></i> Food Advance
                            </a>
                            <a href="{{ route('admin.total.summary') }}" class="quick-btn">
                                <i class="fas fa-chart-pie text-primary"></i> Full Summary
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 text-lg-right mt-3 mt-lg-0">
                        <div class="month-pill shadow-sm">
                            <i class="far fa-calendar-check text-warning"></i>
                            <span>Running Month: <strong>{{ $currentMonth ?? date('F') }}
                                    {{ date('Y') }}</strong></span>
                        </div>
                        <div class="text-white-50 small mt-2 d-none d-lg-block">
                            <i class="far fa-clock mr-1"></i> Today: {{ date('l, d M Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Metrics Grid (7 Core Stat Cards) -->
            <div class="row">

                <!-- 1. Total Members -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-indigo">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Total Active Members</div>
                                    <div class="m-value">
                                        @php
                                            $totMember = 0;
                                            if (isset($members) && count($members) > 0) {
                                                $totMember = $members[0]->total_member ?? 0;
                                            }
                                        @endphp
                                        {{ $totMember }}
                                    </div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="fas fa-user-check text-success mr-1"></i>Active List
                            </span>
                            <a href="{{ route('admin.view-member') }}" class="m-footer-link">
                                View all <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Meals -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-emerald">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Total Meals</div>
                                    <div class="m-value">{{ number_format($total_meal) }}</div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                            </span>
                            <a href="{{ route('admin.view.meal') }}" class="m-footer-link">
                                Meal Sheet <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Meal Rate -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-amber">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Meal Rate</div>
                                    <div class="m-value">Tk. {{ number_format($meal_rate, 2) }}</div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                            </span>
                            <a href="{{ route('admin.view.meal') }}" class="m-footer-link">
                                <a href="{{ route('admin.total.summary') }}" class="m-footer-link">
                                    Calculations <i class="fas fa-arrow-right"></i>
                                </a>
                        </div>
                    </div>
                </div>

                <!-- 4. Total Payment -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-blue">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Overall Fund Collected</div>
                                    <div class="m-value">Tk. {{ number_format($fund, 2) }}</div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                            </span>
                            <a href="{{ route('admin.view.payment') }}" class="m-footer-link">
                                Payment List <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 5. Total Bazar Expanse -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-rose">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Total Bazar Expanse</div>
                                    <div class="m-value">Tk. {{ number_format($all_ex, 2) }}</div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-shopping-basket"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                            </span>
                            <a href="{{ route('admin.view.expanse') }}" class="m-footer-link">
                                Bazar History <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 6. Food Expanses Due -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-4">
                    @php
                        $isFoodDue = $cash < 0;
                        $dueAmount = $isFoodDue ? abs($cash) : 0;
                    @endphp
                    <div class="m-card {{ $isFoodDue ? 'theme-crimson' : 'theme-slate' }}">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Food Expanses Due</div>
                                    <div class="m-value {{ $isFoodDue ? 'text-danger' : 'text-dark' }}">
                                        Tk. {{ number_format($dueAmount, 2) }}
                                    </div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                @if ($isFoodDue)
                                    <span class="text-danger font-weight-bold"><i
                                            class="fas fa-exclamation-circle mr-1"></i>Deficit Alert</span>
                                @else
                                    <span class="text-success font-weight-bold"><i
                                            class="fas fa-check-circle mr-1"></i>Surplus: Tk.
                                        {{ number_format($cash, 2) }}</span>
                                @endif
                            </span>
                            <a href="{{ route('admin.view.expanse') }}" class="m-footer-link">
                                Bazar Details <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 7. Unpaid Bills -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-4">
                    <div class="m-card theme-purple">
                        <div>
                            <div class="m-header">
                                <div>
                                    <div class="m-title">Unpaid Utility & Rent Bills</div>
                                    <div class="m-value {{ $remaining > 0 ? 'text-warning' : 'text-dark' }}">
                                        Tk. {{ number_format($remaining, 2) }}
                                    </div>
                                </div>
                                <div class="m-icon-wrap">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                            </div>
                        </div>
                        <div class="m-footer">
                            <span class="m-footer-badge">
                                <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                            </span>
                            <a href="{{ route('admin.view.payment') }}" class="m-footer-link">
                                Bills Status <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div><!-- /.row -->

            <!-- Financial Pulse & Collection Progress Card -->
            <div class="row">
                <div class="col-lg-8 col-12 mb-4">
                    <div class="fin-panel">
                        <div class="panel-head">
                            <h5 class="panel-title">
                                <i class="fas fa-balance-scale text-primary"></i>
                                Monthly Food Financial Balance & Expense Flow
                            </h5>
                            <span class="badge badge-light border px-2 py-1 text-muted">
                                {{ $currentMonth ?? date('F') }} {{ date('Y') }}
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 col-6 mb-3">
                                    <div class="fin-stat-box">
                                        <div class="num text-primary">Tk. {{ number_format($food_fund ?? 0, 2) }}</div>
                                        <div class="lbl">Fund Collected</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-6 mb-3">
                                    <div class="fin-stat-box">
                                        <div class="num text-danger">Tk. {{ number_format($all_ex, 2) }}</div>
                                        <div class="lbl">Bazar Expense</div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-3">
                                    @php
                                        $foodCashSurplus = ($food_fund ?? 0) - $all_ex;
                                    @endphp
                                    <div class="fin-stat-box">
                                        <div class="num {{ $foodCashSurplus >= 0 ? 'text-success' : 'text-danger' }}">
                                            Tk. {{ number_format(abs($foodCashSurplus), 2) }}
                                        </div>
                                        <div class="lbl">
                                            {{ $foodCashSurplus >= 0 ? 'Net Cash Surplus' : 'Net Cash Deficit' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bazar Budget Utilization Progress -->
                            @php
                                $currentFoodFund = $food_fund ?? 0;
                                $expensePercentage =
                                    $currentFoodFund > 0
                                        ? round(($all_ex / $currentFoodFund) * 100, 1)
                                        : ($all_ex > 0
                                            ? 100
                                            : 0);
                                $progressColor =
                                    $expensePercentage > 100
                                        ? '#ef4444'
                                        : ($expensePercentage > 85
                                            ? '#f59e0b'
                                            : '#10b981');
                            @endphp
                            <div class="m-progress-wrap">
                                <div class="m-progress-label">
                                    <span>Bazar Expense vs Food Fund Collected</span>
                                    <span>{{ $expensePercentage }}% utilized</span>
                                </div>
                                <div class="m-progress-bar">
                                    <div class="m-progress-fill"
                                        style="width: {{ min($expensePercentage, 100) }}%; background: {{ $progressColor }};">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mt-1">
                                    <span>Spent: Tk. {{ number_format($all_ex, 2) }}</span>
                                    <span>Available Food Fund: Tk. {{ number_format($currentFoodFund, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Collection & Member Standing Summary -->
                <div class="col-lg-4 col-12 mb-4">
                    <div class="fin-panel">
                        <div class="panel-head">
                            <h5 class="panel-title">
                                <i class="fas fa-user-friends text-info"></i> Member's Rent and Utility Collection Status
                            </h5>
                        </div>
                        <div class="panel-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <div>
                                    <span class="text-muted d-block small">Active Mess Members</span>
                                    <strong
                                        class="h5 font-weight-bold text-dark">{{ $membersSummary['total_members'] ?? $totMember }} Members</strong>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-primary px-3 py-1 font-weight-bold"
                                        style="border-radius: 50px;">
                                        {{ $membersSummary['collection_rate'] ?? 0 }}% Collected
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-success small font-weight-bold">
                                    <i class="fas fa-check-circle mr-1"></i>Total Collected from Members
                                </span>
                                <strong class="text-success font-weight-bold">
                                    Tk. {{ number_format($membersSummary['rent_utility_collected'] ?? 0, 2) }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Total Expected Mess Cost</span>
                                <strong class="text-dark font-weight-bold">
                                    Tk. {{ number_format($membersSummary['total_expected_cost'] ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger small font-weight-bold">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Members with Due
                                </span>
                                <span class="badge badge-danger px-2 py-1">
                                    {{ $membersSummary['members_due'] ?? 0 }} Members
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Total Outstanding Due</span>
                                <strong class="text-danger font-weight-bold">
                                    Tk. {{ number_format($membersSummary['rent_utility_due'] ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small font-weight-bold">
                                    <i class="fas fa-wallet mr-1 text-info"></i>Total Advance / Credit
                                </span>
                                <strong class="text-info font-weight-bold">
                                    Tk. {{ number_format($membersSummary['total_paid'] ?? 0, 2) }}
                                </strong>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.view.payment', ['category' => 'rent_utility']) }}"
                                    class="btn btn-outline-primary btn-block btn-sm font-weight-bold"
                                    style="border-radius: 8px;">
                                    <i class="fas fa-file-alt mr-1"></i> View Full Members Statement
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Balances Overview Table -->
            @if (isset($memberBalances) && count($memberBalances) > 0)
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="fin-panel">
                            <div class="panel-head">
                                <h5 class="panel-title">
                                    <i class="fas fa-list-alt text-primary"></i>
                                    Member Monthly Standing Quick Overview
                                </h5>
                                <a href="{{ route('admin.view.payment') }}"
                                    class="btn btn-sm btn-primary px-3 font-weight-bold" style="border-radius: 8px;">
                                    <i class="fas fa-external-link-alt mr-1"></i> View Detailed Breakdown
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="m-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Member Name</th>
                                            <th>Room</th>
                                            <th class="text-center">Meals</th>
                                            <th>Meal Cost</th>
                                            <th>Total Cost</th>
                                            <th>Total Paid</th>
                                            <th>Net Balance</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($memberBalances as $index => $mb)
                                            <tr>
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="font-weight-bold text-dark">
                                                        {{ $mb['member_name'] ?? 'N/A' }}</div>
                                                    <div class="text-muted small">{{ $mb['phone'] ?? '' }}</div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-light border">{{ $mb['room_name'] ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-center font-weight-bold text-dark">
                                                    {{ $mb['meal_count'] ?? 0 }}
                                                </td>
                                                <td>Tk. {{ number_format($mb['meal_cost'] ?? 0, 2) }}</td>
                                                <td class="font-weight-bold">Tk.
                                                    {{ number_format($mb['total_cost'] ?? 0, 2) }}</td>
                                                <td class="text-success font-weight-bold">Tk.
                                                    {{ number_format($mb['total_payment'] ?? 0, 2) }}</td>
                                                <td>
                                                    @php
                                                        $bal = $mb['balance'] ?? 0;
                                                    @endphp
                                                    <strong
                                                        class="{{ $bal < 0 ? 'text-danger' : ($bal > 0 ? 'text-success' : 'text-muted') }}">
                                                        Tk. {{ number_format(abs($bal), 2) }}
                                                    </strong>
                                                </td>
                                                <td class="text-center">
                                                    @if (($mb['balance'] ?? 0) < 0)
                                                        <span class="m-badge m-badge-due">Due</span>
                                                    @elseif(($mb['balance'] ?? 0) > 0)
                                                        <span class="m-badge m-badge-credit">Advance</span>
                                                    @else
                                                        <span class="m-badge m-badge-zero">Clear</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div><!-- /.container-fluid -->
    </section>
@endsection
