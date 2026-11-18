@extends('admin.master')

@section('heading', 'dashboard')
@section('title', 'dashboard')

@section('custom_css')
<style>
/* Mobile: small-box card text responsive fix */
@media (max-width: 767px) {
    .small-box > .inner h3 {
        font-size: 1.3rem !important;
        word-break: break-word;
        line-height: 1.2;
    }
    .small-box > .inner p {
        font-size: 0.75rem !important;
        white-space: normal;
        word-break: break-word;
    }
    .small-box .icon {
        display: none; /* icon লুকাও, জায়গা বাড়বে */
    }
    .small-box > .inner {
        padding: 8px 10px !important;
    }
    .small-box-footer {
        font-size: 0.75rem;
    }
}
/* Extra small screens (< 400px) */
@media (max-width: 400px) {
    .small-box > .inner h3 {
        font-size: 1rem !important;
    }
    .small-box > .inner p {
        font-size: 0.65rem !important;
    }
}
</style>
@endsection

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            @foreach ($members as $member)
                                <h3>{{ $member->total_member }}</h3>
                            @endforeach
                            <p>Total Members</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.view-member') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-olive">
                        <div class="inner">

                            <h3>{{ $total_meal }}</h3>

                            <p>Total Meal</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hamburger"></i>
                        </div>
                        <a href="{{ route('admin.view.meal') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Tk {{ number_format($meal_rate, 2) }}</h3>

                            <p>Meal Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{ route('admin.view.meal') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Tk. {{ $fund }}</h3>

                            <p>Total Payment</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                        <a href="{{ route('admin.view.payment') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3>Tk. {{ $all_ex }}</h3>

                            <p>Total Expanse</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="{{ route('admin.view.expanse') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-navy">
                        <div class="inner">
                            @if ($cash < 0)
                                <h3>Tk {{ number_format($cash, 2) }}</h3>
                            @else
                                <h3>Tk 0.00</h3>
                            @endif
                            <p>Food Expanses Due</p>
                        </div>
                        <div class="icon text-dark">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <a href="{{ route('admin.view.expanse') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>Tk. {{ number_format($remaining, 2) }}</h3>

                            <p>Unpaid Bills</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-alt"></i>
                        </div>
                        <a href="{{ route('admin.view.payment') }}" class="small-box-footer">More info <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
