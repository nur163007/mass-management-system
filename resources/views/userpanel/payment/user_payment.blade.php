@extends('admin.master')

@section('heading', 'User Payment')
@section('title', 'user-payment')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card col-md-8 offset-md-2">
                <div class="row">
                <div class="card-header col-md-4 col-12">
                    <h3 class="font-weight-bolder">View Payment</h3>
                </div>
                <div class="card-header col-md-8 col-10 text-xs text-right">
                    <a href="{{route('user.addPayment')}}" class="viewall bg-cyan"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                      <a href="{{route('user.pendingPayment')}}" class="viewall bg-olive"><i class="fas fa-parking"></i> Pending Payment</a>
                </div>
            </div>
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
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
                                <td>Tk. {{ $payment->payment_amount }}</td>
                                <td>{{ date('M d, Y', strtotime($payment->date ))}}</td>
           
                                <td style="width: 80px">
                                    <a href="{{route('user.edit.payment',$payment->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
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

