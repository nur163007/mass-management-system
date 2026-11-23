@extends('admin.master')

@section('heading', 'User Expanse')
@section('title', 'user-expanse')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Expanses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('user.add.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                        <a href="{{route('user.pending.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Pending Expanse</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <!-- Month Filter -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-5 col-sm-7 col-12">
                            <form method="GET" action="{{ route('admin.view.expanse') }}" id="userMonthFilterForm">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-olive text-white font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-1"></i> Month
                                        </span>
                                    </div>
                                    <select name="month" id="userMonthFilter" class="form-control" onchange="document.getElementById('userMonthFilterForm').submit()">
                                        <option value="">All Months</option>
                                        @foreach($months as $m)
                                            <option value="{{ $m }}" {{ (isset($month) && $month == $m) ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!empty($month))
                                        <div class="input-group-append">
                                            <a href="{{ route('admin.view.expanse') }}" class="btn btn-outline-secondary" title="Reset filter to all months">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7 col-sm-5 col-12 text-md-right mt-2 mt-md-0">
                            @if(!empty($month))
                                <span class="badge badge-info p-2" style="font-size: 13px;">
                                    <i class="fas fa-filter"></i> Filtered by: <strong>{{ $month }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($expanse as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Tk. {{ $item->total }}</td>
                                <td>{{ $item->date }}</td>
                                <td>
                                    @if($item->status == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td style="width: 80px">
                                    <a href="{{route('user.details.expanse',[$item->invoice_no,$item->id])}}" class="btn btn-info btn-xs"> <i class="fa fa-eye"></i> </a>
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
        var base_url = window.location.origin;
            //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });

     
        $(function() {
            $("#all-category").DataTable();
            //   $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //   });
        });

    </script>
@endsection