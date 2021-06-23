@extends('admin.master')

@section('heading', 'Add-expanse')
@section('title', 'Expanse')
@section('main-content')
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card">
            <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3>Add Expanse</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.view.expanse')}}" class="viewall"><i class="fas fa-money-bill-alt"></i> All Expanses</a>
                </div>
            </div>
            @include('admin.includes.message')
            <div class="card-body">
                <form method="POST"  enctype="multipart/form-data" id="form">
                    @csrf
                       <div class="row">
                           <div class="form-group col-md-4">
                               <label for="member_id">Member's Name</label>
                               <select id="member_id" class="custom-select" name="member_id">
                                   <option value="">--select member name--</option>
                                   @foreach ($members as $member)
                                   <option value="{{$member->id}}">{{$member->full_name}}</option>
                                   @endforeach
                               </select>
                               @if ($errors->has('member_id'))
                                   <p class="text-danger">{{ $errors->first('member_id') }}</p>
                               @endif
                           </div>
   
                           <div class="form-group col-md-4">
                               <label for="category_id">Category Name</label>
                               <select id="category_id" class="custom-select" name="category_id">
                                   <option value="">--select category name--</option>
                                   @foreach ($categories as $category)
                                   <option value="{{$category->id}}">{{$category->category_name}}</option>
                                   @endforeach
                               </select>
                               @if ($errors->has('category_id'))
                                   <p class="text-danger">{{ $errors->first('category_id') }}</p>
                               @endif
                           </div>

                           <div class="form-group col-md-4">
                            <label for="date">Date</label>
                            <input class="form-control" type="date" id="date" name="date">

                            @if ($errors->has('date'))
                                <p class="text-danger">{{ $errors->first('date') }}</p>
                            @endif
                        </div>
                           


                           <div class="col-md-12">
                            <div class="row">
                              <div class="col-md-12" id="voucher_details">
                                <div class="row" id="row_1">

                           <div class="form-group col-md-4">
                            <label for="item_name_id">Item Name</label>
                            <select id="item_name_id" class="custom-select select2bs4" name="item_name_id[]">
                                <option value="">----select item----</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('item_name_id'))
                                <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="weight">Weight</label>
                            <input class="form-control" type="text" id="weight" name="weight[]"placeholder="Enter Weight">

                            @if ($errors->has('weight'))
                                <p class="text-danger">{{ $errors->first('weight') }}</p>
                            @endif
                        </div>
                           <div class="form-group col-md-3">
                               <label for="amount">Price</label>
                               <input class="form-control" type="number" id="amount" name="amount[]"placeholder="Enter Price">
   
                               @if ($errors->has('amount'))
                                   <p class="text-danger">{{ $errors->first('amount') }}</p>
                               @endif
                           </div>

                           <div class="col-md-1 text-center">
                            <div class="form-group pt-4">
                                <span style="font-size: 1.5em; color: Tomato;" id="addButton"> 
                                  <i class="far fa-plus-square fa-lg pt-3"></i>
                                </span>
                            </div>
                        </div>

                        </div>

                              </div>
                            </div>
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
            url:"{{route('store.expanse')}}",
            data:form,
            contentType:false,
            cache:false,
            processData:false,
            method:"POST",
            success:function(response){
            // alert(response);
                // alert('successfully stored');
               
                              
              // console.lo()
               if(response == "success"){
                Toast.fire({
                            type:'success',
                            title:'Expanse successfully saved.',
                        });
               }
               $("#form")[0].reset();

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

    // $('#category_id').on("change",function(){
    //     var category_id = $('#category_id').val();
    //    // alert(category_id);
    //     $.ajax({
    //         url:"{{route('expanse.onChange')}}",
    //         data:{id:category_id},
    //         contentType:false,
    //         cache:false,
    //         processData:true,
    //         method:"GET",
    //         success:function(response){
    //             if(response){
    //                 $('#item_name_id').empty();
    //                 $('#item_name_id').focus();
    //                 $('#item_name_id').append('<option>----Please select----</option>');
    //                 $.each(response, function(key,value){
    //                     $('select[name="item_name_id[]"]').append('<option value=" '+value.id+' ">'+value.item_name+'</option>');
    //                 });
    //             }else{
    //                 $('#item_name_id').empty();
    //             }
    //         },
    //         error:function(error){
              
    //         }
    //     });

    // });

 
        var i=1;        

        $("#addButton").click(function (e) {
          e.preventDefault();
          i++;

                            _dynamic_div = ` <div class="row" id="row_`+i+`">

                    <div class="form-group col-md-4">
                    <label for="item_name_id">Item Name</label>
                    <select id="item_name_id" class="custom-select select2bs4" name="item_name_id[]">
                                <option value="">----select item----</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                    @if ($errors->has('item_name_id'))
                        <p class="text-danger">{{ $errors->first('item_name_id') }}</p>
                    @endif
                    </div>

                    <div class="form-group col-md-4">
                    <label for="weight">Weight</label>
                    <input class="form-control" type="text" id="weight" name="weight[]"placeholder="Enter Weight">

                    @if ($errors->has('weight'))
                        <p class="text-danger">{{ $errors->first('weight') }}</p>
                    @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label for="amount">Price</label>
                        <input class="form-control" type="number" id="amount" name="amount[]"placeholder="Enter Price">

                        @if ($errors->has('amount'))
                            <p class="text-danger">{{ $errors->first('amount') }}</p>
                        @endif
                    </div>

                    <div class="col-md-1">
                                                <div class="form-group pt-4">
                                                    <span style="font-size: 1.2em; color: red;" class="btn_remove" id="`+i+`"> 
                                                    <i class="far fa-trash-alt pt-3"></i>
                                                    </span>
                                                </div>
                                            </div>

                    </div>`;
          //console.log(_dynamic_div);
          $('#voucher_details').append(_dynamic_div)
        });

        $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id");
            //console.log(button_id);   
            $('#row_'+button_id+'').remove();  
      });
     

});



</script>
@endsection

