@extends('admin.master')

@section('heading', 'Edit-Meal')
@section('title', 'Meal')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Edit Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.meal')}}" class="viewall"><i class="fas fa-hamburger"></i> All Meals</a>
                </div>
            </div> 
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                 <input type="hidden" value="{{ $meals->id}}" id="mealID" name="mealID">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="members_name">Member's Name</label>
                            <input class="form-control" type="text" id="members_name" name="members_name" value="{{ $meals->full_name}}">
                            @if ($errors->has('members_name'))
                                <p class="text-danger">{{ $errors->first('members_name') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input class="form-control" type="text" id="date" name="date" value="{{ $meals->date}}">
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
        event.preventDefault();
        var form = new FormData(this);
        var my_url = "{{route('admin.update.meal')}}";
        $.ajax({
            url:my_url,
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
           console.log(response);
               
                if(response === "success"){
                //  window.location.reload(); 
                    Toast.fire({
                            type:'success',
                            title:'Meal successfully updated.',
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

