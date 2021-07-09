@extends('admin.master')

@section('heading', 'User Reports')
@section('title', 'report')

@section('main-content')

<div class="col-md-12">
    <div class="card card-secondary col-md-8 offset-md-2" id="generatepanel">
      <div class="card-header p-3">
        <h3 class="card-title font-weight-bolder">User Report</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('user.details.report') }}" method="get">
            @csrf
        <div class="row">
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; Month</label>
        <div class="col-md-12 col-sm-12">
        <select class="form-control select2bs4" name="month" required="" id="month">
        <option value="" data-select2-id="2">--Select Month--</option>
        @foreach ($meals as $meal)
        <option value="{{$meal->month}}">{{$meal->month}}</option>
        @endforeach
        </select> 
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; From Month</label>
        <div class="col-md-12 col-sm-12">
        
         <input type="date" required="" name="from_date" id="from_date" class="form-control" placeholder="From Date For Count Meal">
        </div>
        </div>
        </div>
        <div class="col-sm-4">
        <div class="form-group">
        <label>&nbsp;&nbsp; To Month</label>
        <div class="col-md-12 col-sm-12">
        <input type="date" required="" id="to_date" class="form-control" name="to_date" placeholder="To Date For Count Meal">
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="card-footer">
            <input type="submit" id="generate" class="btn btn-success" name="submit" value="Generate">
            </div>
        </form>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    
  </div>
@endsection

