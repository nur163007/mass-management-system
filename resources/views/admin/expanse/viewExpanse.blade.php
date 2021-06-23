@extends('admin.master')

@section('heading', 'All Expanses')
@section('title', 'view expanse')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3>All Expanses</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>SL NO</th>
                                <th>Member's Name</th>
                               
                                <th>Total Amount</th>
                                <th>Date</th>
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
