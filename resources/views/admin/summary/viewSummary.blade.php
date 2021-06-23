@extends('admin.master')

@section('heading', 'Summary')
@section('title', 'summaries')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            @include('admin.includes.message')
            <div class="card">
                
                    <div class="card-header col-md-12 col-12">
                        <h3 class="text-info">Summary</h3>
                    </div>
                
                <div class="col-md-12 mt-3">
                    
                        <div class="card">
                            <table class="table table-active">
                              
                                <tbody>
                                    <tr>
                                        <th style="width: 20%">Total Meal</th>
                                        <td>{{ $total_meal }}</td>
                                    </tr>  
                                    <tr>
                                        <th style="width: 20%">Total Expanses</th>
                                        <td>Tk. {{ $total_amount }}</td>
                                    </tr>  
                                    <tr>
                                        <th style="width: 20%">Meal Rate</th>
                                        <td> Tk. {{ number_format($meal_rate ,2)}}</td>
                                    </tr>               
                                    {{-- show data using ajax --}}
                               
                                </tbody>
                            </table>
                        </div>
                </div>
            
            <div class="col-md-12">
                <hr>
                <h4 >Total Summary</h4>
                <hr>
            </div>

                

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                                <th>Total Meal</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Dues / Cashback</th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($summary as $total)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $total->full_name }}</td>
                               
                                <td>{{ $total->total_meal }}</td>
                                <td> Tk. {{ number_format(($total->total_meal * $meal_rate),2)}}</td>
                                <td>Tk. {{ $total->payment_amount }}</td>
                                <td> Tk. {{ number_format(($total->payment_amount -($total->total_meal * $meal_rate)),2) }}</td>
                                <td>{{ $total->month }}</td>

                                <td style="width: 80px">
                                    <a href="{{route('admin.editMeal',$total->id)}}" class="btn btn-info btn-xs"> <i class="fa fa-edit"></i> </a>
                                    <a href="{{route('admin.deleteMeal',$total->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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
