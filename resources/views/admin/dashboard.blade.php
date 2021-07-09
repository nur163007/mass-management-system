@extends('admin.master')

@section('heading', 'dashboard')
@section('title', 'dashboard')

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
            <a href="{{ route('admin.view-member') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-olive">
            <div class="inner">
           
              <h3>{{$total_meal}}</h3>
           
              <p>Total Meal</p>
            </div>
            <div class="icon">
              <i class="fas fa-hamburger"></i>
            </div>
            <a href="{{ route('admin.view.meal') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>Tk {{number_format($meal_rate,2)}}</h3>

              <p>Meal Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{route('admin.view.meal')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>Tk. {{$fund}}</h3>

              <p>Total Payment</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill"></i>
            </div>
            <a href="{{route('admin.view.payment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-maroon">
            <div class="inner">
              <h3>Tk. {{$all_ex}}</h3>

              <p>Total Expanse</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{route('admin.view.expanse')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

         <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-navy">
            <div class="inner">
              @if($cash < 0)
              <h3>Tk {{number_format($cash,2)}}</h3>
              @else
              <h3>Tk 0.00</h3>
              @endif
              <p>Total Due</p>
            </div>
            <div class="icon text-dark">
              <i class="fas fa-money-check-alt"></i>
            </div>
            <a href="{{route('admin.view.expanse')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

         <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              @if($cash > 0)
              <h3>Tk {{number_format($cash,2)}}</h3>
              @else
              <h3>Tk 0.00</h3>
              @endif

              <p>Remaining</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill-alt"></i>
            </div>
            <a href="{{route('admin.view.payment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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