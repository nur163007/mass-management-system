@extends('admin.master')

@section('heading', 'User EditMeal')
@section('title', 'user-meal')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card col-md-8 offset-md-2">
            <div class="row">
                <div class="card-header col-md-5 col-4">
                    <h3 class="font-weight-bolder">Edit Meal</h3>
                </div>
                <div class="card-header col-md-7 col-8 text-xs text-right">
                    <a href="{{route('user.viewMeal')}}" class="viewall"><i class="fas fa-hamburger"></i> All Meal</a>
                      <a href="{{route('user.pendingMeal')}}" class="viewall bg-olive"><i class="fas fa-parking"></i> Pending Meal</a>
                </div>
            </div> 
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                    <div class="row">
                      <input type="hidden" value="{{ $meals->id}}" id="mealID" name="mealID">
                    
                        <div class="form-group col-md-12">
                            <label for="date">Date</label>
                            <input type="date" id="date" class="custom-select" name="date" value="{{ $meals->date}}" >
                                
                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="breakfast">Breakfast</label>
                            <input class="form-control" type="number" id="breakfast" name="breakfast" value="{{ $meals->breakfast}}">
                            @if ($errors->has('breakfast'))
                                <p class="text-danger">{{ $errors->first('breakfast') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="lunch">Lunch</label>
                            <input class="form-control" type="number" id="lunch" name="lunch" value="{{ $meals->lunch}}">
                            @if ($errors->has('lunch'))
                                <p class="text-danger">{{ $errors->first('lunch') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="dinner">Dinner</label>
                            <input class="form-control" type="number" id="dinner" name="dinner" value="{{ $meals->dinner}}">
                            @if ($errors->has('dinner'))
                                <p class="text-danger">{{ $errors->first('dinner') }}</p>
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
            url:"{{route('update.user.meal')}}",
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
                            title:'Meal Information successfully updated.',
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

