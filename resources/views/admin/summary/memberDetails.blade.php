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
                        <h3>Meal details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.total.summary')}}" class="viewall"><i class="fas fa-list"></i> Total Summary</a>
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
                                    <td> {{ $to->total_meal }}</td>
                                    @endforeach
                                </tr>  
                                <tr>
                                    <th style="width: 20%">Meal Month</th>
                                    <td>
                                        @php
                                            // Convert short month names to full month names
                                            $monthMap = [
                                                'Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April',
                                                'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August',
                                                'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'
                                            ];
                                            $displayMonth = $monthMap[$members->month] ?? $members->month;
                                        @endphp
                                        {{ $displayMonth }}
                                    </td>
                                    
                                </tr>               
                                {{-- show data using ajax --}}
                           
                            </tbody>
                        </table>
                  
                    {{-- <h5><span class="h5">{{ $members->full_name }}</span></h5>
                    @foreach ($total as $to)
                    <h5>Total Meals: <span class="h5">{{ $to->total_meal}}</span> </h5>
                    @endforeach
                    <h5>Meal Month: <span class="h5">{{ $members->month }}</span> </h5> --}}
                {{-- <h4>Meal Months: <span class="h5"> {{ date('M',strtotime($meals->date)) }}</span></h4> --}}
                
            </div>
        </div>
            </div>
            </div>
            
            <div class="col-md-12">
                <hr>
                <h3 class="text-success">Meal Details</h3>
              
            </div>

                

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Breakfast</th>
                               
                                <th>Lunch</th>
                                <th>Dinner</th>
                                <th>Date</th>
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

                                <td style="width: 80px">
                                    <a href="{{route('admin.editMeal',$details->id)}}" class="btn btn-info btn-xs"> <i class="fa fa-edit"></i> </a>
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
