@extends('admin.master')

@section('heading', 'All Expanses')
@section('title', 'expanse details')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            @include('admin.includes.message')
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">Expanse details</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.view.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> All Expanse</a>
                    </div>
                </div>
                <div class="col-md-12 mt-3 ml-2">
                    <h4 class="text-info">Member's Info.</h4>
                    <div class="card">
                        <div class="row">
                        <div class="col-md-2 mr-2 ml-2">
                    <img style="width: 180px; height:200px;padding:5px;"
                                            src="{{ asset('uploads/members/profile/' . $members->photo) }}" alt="">
                </div>
                <div class="col-md-9 mt-1">

                    <table class="table table-active">
                          
                        <tbody>
                            <tr>
                                <th style="width: 20%">Name</th>
                                <td>{{ $members->full_name }}</td>
                            </tr>  
                            <tr>
                                <th style="width: 20%">Phone</th>
                                <td>0{{ $members->phone_no }}</td>
                            </tr>  
                            <tr>
                                <th style="width: 20%">Total Expanses</th>
                                
                                <td> Tk.{{ $members->total_amount }}</td>
                              
                            </tr>  
                            <tr>
                                <th style="width: 20%">Expanse's Date</th>
                                <td> {{ date('d M, Y',strtotime($members->date)) }}</td>
                                
                            </tr>               
                            {{-- show data using ajax --}}
                       
                        </tbody>
                    </table>
               
            </div>
        </div>
            </div>
            </div>
            
            <div class="col-md-12 mt-3 ml-2">
                <hr>
                <h3 class="text-success">Item Details</h3>
              
            </div>

                

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Item Name</th>
                               
                                <th>Description</th>
                                <th>Weight</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($all_details as $details)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $details->item_name }}</td>
                               
                                <td>{{ $details->item_description }}</td>
                                <td>{{ $details->weight }}</td>
                                <td> Tk.{{ $details->amount }}</td>

                                <td style="width: 80px">
                                    <a href="{{route('edit.expanse',$details->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                    {{-- <a href="#" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a> --}}
                                </td>
                                {{-- {{route('delete.expanse',$details->id)}} --}}
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
