@extends('admin.master')

@section('heading', 'All Payments')
@section('title', 'view payment')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3>All Payments</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.payment')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                               
                                <th>Payment Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->full_name }}</td>
                               
                                <td>Tk. {{ $payment->payment_amount }}</td>
                                <td>{{ date('M d, Y', strtotime($payment->date ))}}</td>

                                <td style="width: 80px">
                                    <a href="{{route('admin.edit.payment',$payment->id)}}" class="btn btn-info btn-xs"> <i class="fa fa-edit"></i> </a>
                                    <a href="{{route('admin.delete.payment',$payment->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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
