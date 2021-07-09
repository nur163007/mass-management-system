@extends('admin.master')

@section('heading', 'User edit-expanse')
@section('title', 'Expanse')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card col-md-8 offset-md-2">
            <div class="row">
                <div class="card-header col-md-4 col-12">
                        <h3 class="font-weight-bolder">Edit Expanses</h3>
                    </div>
                    <div class="card-header col-md-8 col-9 text-xs text-right">
                        <a href="{{route('user.add.expanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> Add Expanse</a>
                        <a href="{{route('user.viewExpanse')}}" class="viewall"><i class="far fa-money-bill-alt"></i> All Expanse</a>
                    </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                    @csrf
                    <input type="hidden" value="{{ $expanses->id}}" id="exDetailID" name="exDetailID">
                       <div class="row">
                           
                           <div class="form-group col-md-6">
                            <label for="item_name_id">Item Name</label>
                            <select id="item_name_id" class="custom-select select2bs4" name="item_name_id">
                                <option value="">----select item name----</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $expanses->item_name_id ? 'selected' : '' }}>{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('item_name_id'))
                                <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                            @endif
                        </div>

                           <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input class="form-control" type="date" id="date" name="date" value="{{ $expanses->date }}">

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                           


                           <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-12" id="voucher_details">
                                <div class="row" id="row_1">

                           

                        <div class="form-group col-md-6">
                            <label for="weight">Weight</label>
                            <input class="form-control" type="text" id="weight" name="weight" value="{{ $expanses->weight }}">

                            @if ($errors->has('weight'))
                                <p class="text-danger">{{ $errors->first('weight') }}</p>
                            @endif
                        </div>
                           <div class="form-group col-md-6">
                               <label for="amount">Price</label>
                               <input class="form-control" type="number" id="amount" name="amount" value="{{ $expanses->amount }}">
   
                               @if ($errors->has('amount'))
                                   <p class="text-danger">{{ $errors->first('amount') }}</p>
                               @endif
                           </div>

                        </div>

                              </div>
                            </div>
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


  //Initialize Select2 Elements
  $('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
})
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
        console.log('clicked update button');
        $.ajax({
            url:"{{route('user.update.expanse')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
            //    alert(response);
                // alert('successfully stored');
                // $("#form")[0].reset();
                if(response == "success"){
                Toast.fire({
                            type:'success',
                            title:'Expanse successfully updated.',
                        });
                }
                else{
                    Toast.fire({
                            type:'error',
                            title:'Something Error Found, Please try again.',
                        });
                }

            //   msg ="<div class='alert alert-dark'>"+response+"</div>";
			// 	      $("#msg").html(msg);
            }
        });
      
    
    }); 

});



</script>
@endsection

