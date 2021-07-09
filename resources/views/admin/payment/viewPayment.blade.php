@extends('admin.master')

@section('heading', 'All Payments')
@section('title', 'view payment')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Payments</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.payment')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Payment</a>
                         <a href="{{route('admin.payment.downloadPdf')}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>
 
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                                <th>Payment Amount</th>
                                <th>Date</th>
                                <th>Status</th>
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
                                <td>
                                    <input type="checkbox" data-size="medium" data-toggle="toggle" data-on="Paid" data-off="Request" id="paymentStatus" data-id="{{ $payment->id}}" {{ $payment->status == 1 ? 'checked' : '' }} >
                                </td>
                                <td style="width: 80px">
                                    <a href="{{route('admin.edit.payment',$payment->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
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
     
      $(document).ready(function(){
           $('body').on('change','#paymentStatus',function(){
               // alert('ok');
          var id=$(this).attr('data-id');

        //   alert(id);
          if(this.checked){
            var status = 1;
          }
          else{
            var status = 0;
          }
          // console.log(status);
        $.ajax({
          // url:'mealStatus/'+id+'/'+status,
            url:"{{url('admin/payment/paymentStatus')}}/"+id+'/'+status,
          // data:{status:status},
          method:'get',
          success:function(success){
            // window.location.reload(); 
            console.log(success);
          },
        });

        });

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
