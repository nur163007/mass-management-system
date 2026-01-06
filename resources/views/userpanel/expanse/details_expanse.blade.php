@extends('admin.master')

@section('heading', 'User Details expanse')
@section('title', 'user-expanse')

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
                        <a href="{{route('user.viewExpanse')}}" class="viewall"><i class="fas fa-list"></i> All Expanse</a>
                        <a href="{{route('user.pending.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Pending Expanse</a>
                    </div>
                </div>

                @if(isset($members))
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
                            @if(isset($expanse_status) && $expanse_status->cost_bearer)
                            <tr>
                                <th style="width: 20%">Cost Will Be Paid By</th>
                                <td>
                                    @if($expanse_status->cost_bearer == 'food_advance')
                                        <span class="badge badge-info">Food Advance (Meal Collection)</span>
                                    @elseif($expanse_status->cost_bearer == 'user')
                                        <span class="badge badge-warning">User Account (Will be deducted from my account)</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
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
            @endif

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Weight</th>
                                <th>Amount</th>
                                @if(isset($expanse_status) && $expanse_status->status == 0)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($all_details as $details)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $details->item_name }}</td>
                                <td>{{ $details->item_description ?? 'N/A' }}</td>
                                <td>{{ $details->weight }}</td>
                                <td> Tk.{{ $details->amount }}</td>
                                @if(isset($expanse_status) && $expanse_status->status == 0)
                                <td style="width: 80px">
                                    <a href="{{route('user.edit.expanse',$details->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                </td>
                                @endif
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