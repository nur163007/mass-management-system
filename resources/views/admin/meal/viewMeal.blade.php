@extends('admin.master')

@section('heading', 'All Meals')
@section('title', 'view meals')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">View Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.add.meal')}}" class="viewall"><i class="fas fa-hamburger"></i> Add Meal</a>

                     <a href="{{route('admin.meal.downloadPdf')}}" class="viewall bg-cyan"><i class="far fa-file-pdf"></i> Download pdf</a>
                </div>
            </div>
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th> Member's Name</th>
                                <th> Total Meal</th>
                                <th>Month</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                         @foreach ($meals as $details)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $details->full_name}}</td>
                               
                                <td>{{ $details->total_meal }}</td>
                                <td>
                                    @php
                                        // Convert short month names to full month names
                                        $monthMap = [
                                            'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                            'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                            'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                        ];
                                        $displayMonth = $monthMap[$details->month] ?? $details->month;
                                    @endphp
                                    {{ $displayMonth }}
                                </td>
                                <td>
                                    @if($details->status == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td style="width: 80px">
                                    <a href="{{route('admin.mealDetails',$details->members_id)}}" class="btn btn-info btn-xs"> <i class="fas fa-eye"></i> </a>
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
