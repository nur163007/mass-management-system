@extends('admin.master')

@section('heading', 'Add-members')
@section('title', 'member')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        
        <div class="card">
            <div class="row">
            <div class="card-header col-md-6 col-6">
                <h3>Add Members</h3>
            </div>
            <div class="card-header col-md-6 col-6 text-right">
                <a href="{{route('admin.view-member')}}" class="viewall"><i class="fas fa-users"></i> All Members</a>
            </div>
        </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="full_name">Full Name</label>
                            <input class="form-control" type="text" id="full_name" name="full_name">
                            @if ($errors->has('full_name'))
                                <p class="text-danger">{{ $errors->first('full_name') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no">Phone No</label>
                            <input class="form-control" type="text" id="phone_no" name="phone_no">
                            @if ($errors->has('phone_no'))
                                <p class="text-danger">{{ $errors->first('phone_no') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">Permanent Address</label>
                            <input class="form-control" type="text" id="address" name="address">
                            @if ($errors->has('address'))
                                <p class="text-danger">{{ $errors->first('address') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" id="email" name="email">
                            @if ($errors->has('email'))
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="photo">Photo</label>
                            <input class="form-control-file" type="file" id="photo" name="photo">

                            @if ($errors->has('photo'))
                                <p class="text-danger">{{ $errors->first('photo') }}</p>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="nid_photo">NID Photo</label>
                            <input class="form-control-file" type="file" id="nid_photo" name="nid_photo">

                            @if ($errors->has('nid_photo'))
                                <p class="text-danger">{{ $errors->first('nid_photo') }}</p>
                            @endif
                        </div>
                    </div>
                    <input class="btn btn-success" type="submit" id="submit" name="submit" value="Submit">
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
            url:"{{route('store.member')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
                // alert('successfully stored');
                $("#form")[0].reset();
               

                Toast.fire({
                            type:'success',
                            title:'Members information successfully saved.',
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