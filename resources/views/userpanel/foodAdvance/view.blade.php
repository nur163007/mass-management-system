@extends('admin.master')

@section('heading', 'Food Advance Details')
@section('title', 'view-food-advance')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Food Advance Details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.foodAdvance.index')}}" class="viewall"><i class="fas fa-list"></i> Food Advance List</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 40%; background-color: #f4f4f4;">Amount</th>
                                    <td>Tk. {{ number_format($advance->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Date</th>
                                    <td>{{ $advance->date }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Month</th>
                                    <td>{{ $advance->month }}</td>
                                </tr>
                                <tr>
                                    <th style="background-color: #f4f4f4;">Status</th>
                                    <td>
                                        @if($advance->status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($advance->notes)
                                <tr>
                                    <th style="background-color: #f4f4f4;">Notes</th>
                                    <td>{{ $advance->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

