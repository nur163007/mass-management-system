@extends('admin.master')

@section('heading', 'User Pending payment')
@section('title', 'user-payment')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Pending Payment</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('user.addPayment')}}" class="viewall bg-cyan"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                      <a href="{{route('user.viewPayment')}}" class="viewall bg-olive"><i class="far fa-money-bill"></i> View Payment</a>
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
                        
                     @foreach ($pending as $pen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>Tk. {{ $pen->payment_amount }}</td>
                                <td>{{ date('M d, Y', strtotime($pen->date ))}}</td>
           
                                <td style="width: 80px">
                                    <span class="badge badge-warning">Pending</span>
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

