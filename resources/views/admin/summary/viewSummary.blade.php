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
                    
                    <div class="row">
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Meal</span>
                              <span class="info-box-number text-center text-muted mb-0">{{ $total_meal }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Expanses</span>
                              <span class="info-box-number text-center text-muted mb-0">Tk. {{ $total_amount }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="info-box bg-light">
                            <div class="info-box-content">
                              <span class="info-box-text text-center text-muted">Total Meal Rate</span>
                              <span class="info-box-number text-center text-muted mb-0">Tk. {{ number_format($meal_rate ,2)}}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            
      
                <hr>
            
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
                                <td>Tk. {{ $total->payment_amount ? $total->payment_amount : '00' }}</td>
                                <td> Tk. {{ number_format(($total->payment_amount -($total->total_meal * $meal_rate)),2) }}</td>
                                <td>{{ $total->month }}</td>

                                <td style="width: 80px">
                                    <a href="{{route('admin.memberDetails',$total->id)}}" class="btn btn-info btn-xs"> <i class="fa fa-eye"></i> </a>
                                    {{-- <a href="{{route('admin.deleteMeal',$total->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a> --}}
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
