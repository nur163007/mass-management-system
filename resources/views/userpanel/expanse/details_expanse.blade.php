@extends('admin.master')

@section('heading', 'User Details expanse')
@section('title', 'user-expanse')

@section('main-content')
     <section class="content">
        <div class="container-fluid">
            <div class="card col-md-8 offset-md-2">
                <div class="row">
                    <div class="card-header col-md-4 col-12">
                        <h3 class="font-weight-bolder">Details Expanse</h3>
                    </div>
                    <div class="card-header col-md-8 col-10 text-xs text-right">
                        <a href="{{route('user.viewExpanse')}}" class="viewall"><i class="far fa-money-bill"></i> All Expanse</a>
                        <a href="{{route('user.pending.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Pending Expanse</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th>Item Name</th>
                                <th>Weight</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        @foreach ($all_details as $details)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $details->item_name }}</td>
                                <td>{{ $details->weight }}</td>
                                <td>Tk. {{ $details->amount }}</td>
                                <td style="width: 80px">
                                    <a href="{{route('user.edit.expanse',[$details->invoice_no,$details->id])}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
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