@extends('admin.master')

@section('heading', 'Food Advance')
@section('title', 'food-advance')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Food Advance List</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.foodAdvance.create')}}" class="viewall"><i class="fas fa-plus"></i> Add Food Advance</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('user.foodAdvance.index') }}" id="filterForm">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fas fa-filter"></i> Filter by Month</label>
                                    <select name="month" class="form-control" onchange="document.getElementById('filterForm').submit()" style="border-radius: 5px;">
                                        <option value="">All Months</option>
                                        @foreach($months as $month)
                                            <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            @foreach ($advances as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>Tk. {{ number_format($item->amount, 2) }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->month }}</td>
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td style="width: 120px">
                                        @if($item->status == 0)
                                            <a href="{{route('user.foodAdvance.edit', $item->id)}}" class="btn btn-warning btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                        @endif
                                        <a href="{{route('user.foodAdvance.view', $item->id)}}" class="btn btn-info btn-xs"> <i class="fa fa-eye"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script>
        $(function() {
            $("#all-category").DataTable();
        });
    </script>
@endsection

