@extends('admin.master')

@section('heading', 'All Expanses')
@section('title', 'view expanse')

@section('main-content') 
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Expanses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                         <a href="{{route('admin.expanse.downloadPdf')}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                               
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($bazars as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->full_name }}</td>
                               
                                <td>Tk. {{ $item->total }}</td>
                                <td>{{ $item->expanse_date }}</td>
                                <td>
                                    <input type="checkbox" data-size="medium" data-toggle="toggle" data-on="Active" data-off="Request" id="expanseStatus" data-id="{{ $item->id}}" {{ $item->status == 1 ? 'checked' : '' }} >
                                </td>
                                <td style="width: 80px">
                                    <a href="{{route('details.expanse',$item->invoice_no)}}" class="btn btn-info btn-xs"> <i class="fa fa-eye"></i> </a>
                                    <a href="{{route('delete.expanse',$item->invoice_no)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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
           $('body').on('change','#expanseStatus',function(){
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
            url:"{{url('admin/expanse/expanseStatus')}}/"+id+'/'+status,
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
            // scrollX: '100vh',
            // scrollY: '100vh'

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
