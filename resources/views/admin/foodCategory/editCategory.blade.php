@extends('admin.master')

@section('heading', 'Edit-Category')
@section('title', 'EditCategory')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Edit Category</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.category')}}" class="viewall"><i class="fas fa-dolly-flatbed"></i> All Categories</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST" id="form">
                 @csrf
                 <input type="hidden" value="{{ $categories->id}}" id="categoryID" name="categoryID">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="category_name">Category Name</label>
                            <input class="form-control" type="text" id="category_name" name="category_name"value="{{ $categories->category_name}}">
                            @if ($errors->has('category_name'))
                                <p class="text-danger">{{ $errors->first('category_name') }}</p>
                            @endif
                        </div>
                    </div>
                    <input class="btn btn-success" type="submit" id="submit" name="submit" value="Update">
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
var base_url = window.location.origin;
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
    // console.log("clicked");
    event.preventDefault();
    var formData = $(this).serialize();
    var my_url = "{{route('update.category')}}";
    $.ajax({
        url:my_url,
        data:formData,
        method:"POST",
        success:function(response){
           
        //    alert(response);
            // alert('successfully stored');
           
            if(response === "success"){
             // window.location.reload(); 
                Toast.fire({
                        type:'success',
                        title:'Category successfully updated.',
                        });
           }else{
            Toast.fire({
                        type:'error',
                        title:'Something Error Found, Please try again.',
                    });
           }
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

