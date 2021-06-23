@extends('admin.master')

@section('heading', 'Edit-payment')
@section('title', 'Payment')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3>Edit Payment</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.payment')}}" class="viewall"><i class="fas fa-money-bill-alt"></i> All Payments</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                    @csrf
                    <input type="hidden" value="{{ $payments->id}}" id="paymentID" name="paymentID">
                       <div class="row">
                           <div class="form-group col-md-6">
                               <label for="member_id">Member's Name</label>
                               <select id="member_id" class="custom-select select2bs4" name="member_id">
                                   <option value="">----select members name----</option>
                                   @foreach ($members as $member)
                                   <option value="{{ $member->id }}" {{ $member->id == $payments->member_id ? 'selected' : '' }} >{{$member->full_name}}</option>
                                   @endforeach
                               </select>
                               @if ($errors->has('member_id'))
                                   <p class="text-danger">{{ $errors->first('member_id') }}</p>
                               @endif
                           </div>
   
                        
                           <div class="form-group col-md-6">
                            <label for="amount">Payment Amount</label>
                            <input class="form-control" type="number" id="amount" name="amount" value="{{ $payments->payment_amount }}">

                            @if ($errors->has('amount'))
                                <p class="text-danger">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>

                           <div class="form-group col-md-6">
                            <label for="date"> Payment Date</label>
                            <input class="form-control" type="date" id="date" name="date" value="{{ $payments->date }}">

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
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
        $.ajax({
            url:"{{route('admin.update.payment')}}",
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
                            title:'Payment successfully updated.',
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

