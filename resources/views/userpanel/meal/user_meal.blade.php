@extends('admin.master')

@section('heading', 'User Meal')
@section('title', 'user_meal')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">View Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('user.addMeal')}}" class="viewall bg-cyan"><i class="fas fa-hamburger"></i> Add Meal</a>
                      <a href="{{route('user.pendingMeal')}}" class="viewall bg-olive"><i class="fas fa-parking"></i> Pending Meal</a>
                </div>
            </div>
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th> Total Meal</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        
					 @foreach ($meals as $meal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $meal->total_meal }}</td>
                                <td>{{ $meal->month }}</td> 
                                <td style="width: 80px">
                                    <a href="{{route('user.detailsMeal',[$meal->id,$meal->month])}}" class="btn btn-info btn-xs"> <i class="fas fa-eye"></i> </a>
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