@extends('admin.master')

@section('heading', 'All Categories')
@section('title', 'view category')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">View Categories</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.category')}}" class="viewall"><i class="fas fa-dolly-flatbed"></i> Add Categories</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-olive">
                            <tr>
                                <th>SL NO</th>
                                <th> Category Name</th>
                                <th>Item Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        
                         @foreach ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->category_name }}</td>
                               
                                <td><img style="width: 60px; height:60px"
                                            src="{{ asset('uploads/category/' . $category->photo) }}" alt=""></td>
                               
                                <td style="width: 80px">
                                    <a href="{{route('admin.editCategory',$category->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                    <a href="{{route('admin.deleteCategory',$category->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
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
