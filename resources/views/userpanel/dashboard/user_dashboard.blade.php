@extends('admin.master')

@section('heading', 'User Dashboard')
@section('title', 'user-dashboard')

@section('main-content')
<section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
           
              <h3> {{$total_meal}} </h3>
            
              <p>Total Meal</p>
            </div>
            <div class="icon">
              <i class="fas fa-hamburger"></i>
            </div>
            <a href="{{ route('user.viewMeal') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-success">
            <div class="inner">
              <h3>Tk. {{ number_format($fund,2)}}</h3>

              <p>Total Payment (Food Advance)</p>
            </div>
            <div class="icon">
              <i class="fas fa-comment-dollar"></i>
            </div>
            <a href="{{route('user.viewPayment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-warning">
            <div class="inner">
              @if($meal_due > 0)
              <h3>Tk {{ number_format($meal_due,2)}}</h3>
              @else
              <h3>Tk 0.00</h3>
              @endif
              <p>Meal Due</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill"></i>
            </div>
            <a href="{{route('user.viewPayment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>Tk {{ number_format($cashback,2)}}</h3>
              <p>Cashback</p>
            </div>
            <div class="icon">
              <i class="fas fa-money-bill-alt"></i>
            </div>
            <a href="{{route('user.viewPayment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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