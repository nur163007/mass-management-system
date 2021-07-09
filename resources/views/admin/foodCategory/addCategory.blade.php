@extends('admin.master')

@section('heading', 'Add-Category')
@section('title', 'Category')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Add Category</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.category')}}" class="viewall"><i class="fas fa-dolly-flatbed"></i> All Categories</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="category_name">Category Name</label>
                            <input class="form-control" type="text" id="category_name" name="category_name">
                            @if ($errors->has('category_name'))
                                <p class="text-danger">{{ $errors->first('category_name') }}</p>
                            @endif
                        </div>

                       
                        <div class="form-group col-md-6">
                            <label for="photo"> Photos</label>
                            <input class="form-control-file" type="file" id="photo" name="photo">

                            @if ($errors->has('photo'))
                                <p class="text-danger">{{ $errors->first('photo') }}</p>
                            @endif
                        </div>
                     
                    </div>
                    <input class="btn btn-success" type="submit" id="submit" name="submit" value="submit">
                </form>
            </div>
        </div>

        <!-- /.row -->

    </div><!-- /.container-fluid -->
</section>
@endsection

{{-- data store with ajax --}}
@section('custom_js')

<script>

    //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });


    // pass the csrf token for post method.
       $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

$(document).ready(function(){

    $('#form').on("submit",function(event){
        event.preventDefault();
        var form = new FormData(this);
        $.ajax({
            url:"{{route('store.category')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
            //    alert(response);
                // alert('successfully stored');
                $("#form")[0].reset();
               
                Toast.fire({
                            type:'success',
                            title:'Category successfully saved.',
                        });

            //   msg ="<div class='alert alert-dark'>"+response+"</div>";
			// 	      $("#msg").html(msg);
            },
            error:function(error){
                Toast.fire({
                            type:'error',
                            title:'Something Error Found, Please try again.',
                        });
            }
        });
      
    
    });
});


</script>
@endsection

