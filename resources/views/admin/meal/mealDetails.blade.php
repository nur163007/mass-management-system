@extends('admin.master')

@section('heading', 'All Meals')
@section('title', 'meal details')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        @include('admin.includes.message')
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Meal details</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.meal')}}" class="viewall"><i class="fas fa-hamburger"></i> All Meals</a>
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <h4 class="text-info">Member's Info.</h4>
                <div class="card">
                    <div class="row">
                        <div class="col-md-2">
                            <img style="width: 150px; height:150px;padding:5px"
                            src="{{ asset('uploads/members/profile/' . $members->photo) }}" alt="">


                        </div>
                        <div class="col-md-8 mt-2 ">


                            <table class="table table-active">

                                <tbody>
                                    <tr>
                                        <th style="width: 20%">Name</th>
                                        <td>{{ $members->full_name }}</td>
                                    </tr>  
                                    <tr>
                                        <th style="width: 20%">Total Meals</th>
                                        @foreach ($total as $to)
                                        <td> {{ $to->total_meal ? $to->total_meal : 0}}</td>
                                        @endforeach
                                    </tr>  
                                    <tr>
                                        <th style="width: 20%">Meal Month</th>
                                        <td> {{ $members->month }}</td>

                                    </tr>               
                                    {{-- show data using ajax --}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <hr>
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder text-success">Meal details</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.everymeal.downloadPdf',$members->id)}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                </div>
            </div>
            </div>



            <div class="card-body">
                <table id="all-category" class="table table-bordered table-hover">
                    <thead class="bg-cyan">
                        <tr>
                            <th>SL NO</th>
                            <th>Breakfast</th>

                            <th>Lunch</th>
                            <th>Dinner</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                        {{-- show data using ajax --}}
                        @foreach ($meals as $details)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $details->breakfast }}</td>

                            <td>{{ $details->lunch }}</td>
                            <td>{{ $details->dinner }}</td>
                            <td>{{ date('M d,Y',strtotime($details->date)) }}</td>
                            <td>
                                <input type="checkbox" data-size="medium" data-toggle="toggle" data-on="Active" data-off="Request" id="mealStatus" data-id="{{ $details->id}}" {{ $details->status == 1 ? 'checked' : '' }} >
                            </td>
                            <td style="width: 80px">
                                <a href="{{route('admin.editMeal',$details->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                <a href="{{route('admin.deleteMeal',$details->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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
            $(document).ready(function(){
             $('body').on('change','#mealStatus',function(){
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
          url:"{{url('admin/meal/mealStatus')}}/"+id+'/'+status,
          // data:{status:status},
          method:'get',
          success:function(success){
            window.location.reload(); 
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
