@extends('admin.master')

@section('heading', 'Edit-Item')
@section('title', 'Edit-Item')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3 class="font-weight-bolder">Edit Item</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.foodItem')}}" class="viewall"><i class="fas fa-dolly-flatbed"></i> All Items</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                 @csrf
                 <input type="hidden" value="{{ $items->id}}" id="itemID" name="itemID">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="item_name">Item Name</label>
                            <input class="form-control" type="text" id="item_name" name="item_name" value="{{ $items->item_name }}">
                            @if ($errors->has('item_name'))
                                <p class="text-danger">{{ $errors->first('item_name') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="item_description">Item Description</label>
                            <textarea rows="3"col="15"class="form-control" type="text" name="item_description">{{ $items->item_description }}</textarea>
                            @if ($errors->has('item_description'))
                                <p class="text-danger">{{ $errors->first('item_description') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="item_photo"> Photos</label>
                            <input class="form-control-file" type="file" id="item_photo" name="item_photo">

                            @if ($errors->has('item_photo'))
                                <p class="text-danger">{{ $errors->first('item_photo') }}</p>
                            @endif
                        </div>
                        <div class="form-group col-md-2">
                            <img style="width: 60px; height: 60px" src="{{asset('uploads/foodItems/'.$items->item_photo)}}">
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
        var my_url ="{{route('update.foodItem')}}";
        $.ajax({
            url:my_url,
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
                console.log(response);
            //    alert(response);
                // alert('successfully stored');
                if(response === "success"){
            //  window.location.reload(); 
                Toast.fire({
                        type:'success',
                        title:'Item successfully updated.',
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

