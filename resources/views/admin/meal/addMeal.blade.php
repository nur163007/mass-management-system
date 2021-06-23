@extends('admin.master')

@section('heading', 'Add-meal')
@section('title', 'meal')

@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3>Add Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.meal')}}" class="viewall"><i class="fas fa-hamburger"></i> All Meals</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="members_id">Member's Name</label>
                            <select id="members_id" class="custom-select" name="members_id">
                                <option value="">--select name--</option>
                                @foreach ($members as $member)
                                <option value="{{$member->id}}">{{$member->full_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('members_id'))
                                <p class="text-danger">{{ $errors->first('members_id') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input type="date" id="date" class="custom-select" name="date" >
                                
                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="breakfast">Breakfast</label>
                            <input class="form-control" type="number" id="breakfast" name="breakfast">
                            @if ($errors->has('breakfast'))
                                <p class="text-danger">{{ $errors->first('breakfast') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="lunch">Lunch</label>
                            <input class="form-control" type="number" id="lunch" name="lunch">
                            @if ($errors->has('lunch'))
                                <p class="text-danger">{{ $errors->first('lunch') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="dinner">Dinner</label>
                            <input class="form-control" type="number" id="dinner" name="dinner">
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
            url:"{{route('store.meal')}}",
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
                            title:'Meal Information successfully saved.',
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