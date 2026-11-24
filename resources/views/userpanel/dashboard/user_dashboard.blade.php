@extends('admin.master')

@section('heading', 'My Dashboard')
@section('title', 'My Dashboard | MMS')

@section('custom_css')
<style>
/* Modern Dashboard Styling */
:root {
    --card-radius: 16px;
    --transition-fast: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

/* User Hero Banner */
.user-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    border-radius: var(--card-radius);
    color: #ffffff;
    padding: 24px 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
}
.user-hero::after {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.user-hero .hero-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: -0.3px;
}
.user-hero .hero-subtitle {
    font-size: 0.9rem;
    color: #94a3b8;
    margin-bottom: 0;
}
.user-hero .month-pill {
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
.user-hero .quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 14px;
}
.user-hero .quick-btn {
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
.user-hero .quick-btn:hover {
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

/* Color Palettes */
.theme-emerald .m-icon-wrap { background: linear-gradient(135deg, #10b981, #059669); }
.theme-blue .m-icon-wrap { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
.theme-crimson .m-icon-wrap { background: linear-gradient(135deg, #ef4444, #dc2626); }
.theme-slate .m-icon-wrap { background: linear-gradient(135deg, #64748b, #475569); }
.theme-purple .m-icon-wrap { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

/* Details Card */
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
.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}
.detail-item:last-child {
    border-bottom: none;
}

@media (max-width: 767px) {
    .user-hero {
        padding: 18px;
    }
    .user-hero .hero-title {
        font-size: 1.25rem;
    }
    .user-hero .month-pill {
        width: 100%;
        justify-content: center;
        margin-top: 10px;
    }
    .user-hero .quick-btn {
        flex: 1 1 45%;
        justify-content: center;
    }
}
</style>
@endsection

@section('main-content')
<section class="content pt-2 pb-4">
    <div class="container-fluid">

        <!-- Modern Hero Banner -->
        <div class="user-hero">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-12">
                    <h2 class="hero-title">
                        <i class="fas fa-user-circle text-success mr-2"></i>Welcome to Your Portal
                    </h2>
                    <p class="hero-subtitle">
                        Track your daily meals, monthly food advance, and personal balance in real-time.
                    </p>
                    <div class="quick-actions">
                        <a href="{{ route('user.viewMeal') }}" class="quick-btn">
                            <i class="fas fa-utensils text-success"></i> My Meals
                        </a>
                        <a href="{{ route('payment.myPayment') }}" class="quick-btn">
                            <i class="fas fa-receipt text-info"></i> Payment History
                        </a>
                        <a href="{{ route('user.addPayment') }}" class="quick-btn">
                            <i class="fas fa-plus text-warning"></i> Add Payment
                        </a>
                        <a href="{{ route('admin.foodAdvance.create') }}" class="quick-btn">
                            <i class="fas fa-hand-holding-usd text-primary"></i> Food Advance
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 text-lg-right mt-3 mt-lg-0">
                    <div class="month-pill shadow-sm">
                        <i class="far fa-calendar-check text-success"></i>
                        <span>Running Month: <strong>{{ $currentMonth ?? date('F') }} {{ date('Y') }}</strong></span>
                    </div>
                    <div class="text-white-50 small mt-2 d-none d-lg-block">
                        <i class="far fa-clock mr-1"></i> Today: {{ date('l, d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Alerts (if any) -->
        @if(isset($notifications) && count($notifications) > 0)
            @foreach($notifications as $notif)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>{{ $notif['title'] ?? 'Notice' }}:</strong> {{ $notif['message'] ?? '' }}
                @if(isset($notif['action']))
                    <a href="{{ url($notif['action']) }}" class="btn btn-sm btn-dark ml-3 py-0 px-2 font-weight-bold">View</a>
                @endif
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endforeach
        @endif

        <!-- Modern Metric Cards (4 User KPIs) -->
        <div class="row">

            <!-- 1. My Total Meals -->
            <div class="col-lg-3 col-6 mb-4">
                <div class="m-card theme-emerald">
                    <div>
                        <div class="m-header">
                            <div>
                                <div class="m-title">My Total Meals</div>
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
                        <a href="{{ route('user.viewMeal') }}" class="m-footer-link">
                            My Sheet <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. My Total Payment -->
            <div class="col-lg-3 col-6 mb-4">
                <div class="m-card theme-blue">
                    <div>
                        <div class="m-header">
                            <div>
                                <div class="m-title">My Total Payment</div>
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
                        <a href="{{ route('user.viewPayment') }}" class="m-footer-link">
                            Payments <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. My Meal Due -->
            <div class="col-lg-3 col-6 mb-4">
                @php
                    $isDue = ($meal_due > 0);
                @endphp
                <div class="m-card {{ $isDue ? 'theme-crimson' : 'theme-slate' }}">
                    <div>
                        <div class="m-header">
                            <div>
                                <div class="m-title">Meal Due</div>
                                <div class="m-value {{ $isDue ? 'text-danger' : 'text-success' }}">
                                    @if($isDue)
                                        Tk. {{ number_format($meal_due, 2) }}
                                    @else
                                        Tk. 0.00
                                    @endif
                                </div>
                            </div>
                            <div class="m-icon-wrap">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="m-footer">
                        <span class="m-footer-badge">
                            @if($isDue)
                                <span class="text-danger font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i>Due</span>
                            @else
                                <span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Clear</span>
                            @endif
                        </span>
                        <a href="{{ route('user.viewPayment') }}" class="m-footer-link">
                            Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4. Cashback / Advance Surplus -->
            <div class="col-lg-3 col-6 mb-4">
                @php
                    $hasCashback = ($cashback > 0);
                @endphp
                <div class="m-card {{ $hasCashback ? 'theme-emerald' : 'theme-purple' }}">
                    <div>
                        <div class="m-header">
                            <div>
                                <div class="m-title">Cashback / Advance</div>
                                <div class="m-value {{ $hasCashback ? 'text-success' : 'text-dark' }}">
                                    Tk. {{ number_format($cashback, 2) }}
                                </div>
                            </div>
                            <div class="m-icon-wrap">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    <div class="m-footer">
                        <span class="m-footer-badge">
                            <i class="far fa-calendar-alt text-primary mr-1"></i>{{ $currentMonth ?? date('F') }}
                        </span>
                        <a href="{{ route('user.viewPayment') }}" class="m-footer-link">
                            More info <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->

        <!-- Personal Monthly Financial Summary Panel -->
        <div class="row">
            <div class="col-lg-7 col-12 mb-4">
                <div class="fin-panel">
                    <div class="panel-head">
                        <h5 class="panel-title">
                            <i class="fas fa-chart-pie text-primary"></i>
                            My Account Standing ({{ $currentMonth ?? date('F') }} {{ date('Y') }})
                        </h5>
                    </div>
                    <div class="panel-body">
                        <div class="detail-item">
                            <div>
                                <strong>Total Meals Consumed</strong>
                                <span class="text-muted d-block small">Daily breakfast, lunch, and dinner count</span>
                            </div>
                            <div class="h5 font-weight-bold text-dark mb-0">
                                {{ number_format($total_meal) }} Meals
                            </div>
                        </div>

                        <div class="detail-item">
                            <div>
                                <strong>Calculated Meal Rate</strong>
                                <span class="text-muted d-block small">Mess average rate for this month</span>
                            </div>
                            <div class="h5 font-weight-bold text-dark mb-0">
                                Tk. {{ number_format($balance['meal_rate'] ?? 0, 2) }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div>
                                <strong>My Meal Cost</strong>
                                <span class="text-muted d-block small">Meals multiplied by current rate</span>
                            </div>
                            <div class="h5 font-weight-bold text-primary mb-0">
                                Tk. {{ number_format($balance['meal_cost'] ?? 0, 2) }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div>
                                <strong>Total Payments Made</strong>
                                <span class="text-muted d-block small">Food advance and meal payments</span>
                            </div>
                            <div class="h5 font-weight-bold text-success mb-0">
                                Tk. {{ number_format($fund, 2) }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div>
                                <strong>Net Account Status</strong>
                                <span class="text-muted d-block small">Surplus advance or remaining due</span>
                            </div>
                            <div>
                                @if($meal_due > 0)
                                    <span class="badge badge-danger px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                        Due: Tk. {{ number_format($meal_due, 2) }}
                                    </span>
                                @elseif($cashback > 0)
                                    <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                        Advance: Tk. {{ number_format($cashback, 2) }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                        Balanced: Tk. 0.00
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation & Tips -->
            <div class="col-lg-5 col-12 mb-4">
                <div class="fin-panel">
                    <div class="panel-head">
                        <h5 class="panel-title">
                            <i class="fas fa-lightbulb text-warning"></i>
                            Quick Actions & Guidelines
                        </h5>
                    </div>
                    <div class="panel-body">
                        <p class="text-muted small">
                            Keep your mess accounts up to date. You can submit food advances and regular payments online anytime.
                        </p>
                        <div class="list-group list-group-flush mb-3">
                            <a href="{{ route('admin.foodAdvance.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0 border-top-0">
                                <span><i class="fas fa-hand-holding-usd text-primary mr-2"></i>Submit Food Advance</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('user.addPayment') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0">
                                <span><i class="fas fa-credit-card text-success mr-2"></i>Submit Regular Payment</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('payment.myPayment') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0">
                                <span><i class="fas fa-receipt text-info mr-2"></i>Download Payment Receipts</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('user.viewMeal') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-0 border-bottom-0">
                                <span><i class="fas fa-calendar-check text-warning mr-2"></i>Review Monthly Meals</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
@endsection
