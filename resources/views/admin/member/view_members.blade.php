@extends('admin.master')

@section('heading', 'All members')
@section('title', 'view member')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3>All Members</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add-member')}}" class="viewall"><i class="far fa-user"></i> Add Member</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th>Name</th>
                                <th>Phone no</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>NID Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            {{-- show data using ajax --}}
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection


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

                    $(document).ready(function(){
        function showData(){
            $.ajax({
                url:"{{route('admin.showdata')}}",
                method:"GET",
                success: function(response){
                    console.log(response);
                    output="";
				if(response){
					x=response;
				}else{
					x="";
				}
				for(i=0;i<x.length;i++){
				output+="<tr><td>"+x[i].id+ 
				       "</td><td>"+x[i].full_name+ "</td><td>"+x[i].phone_no+ "</td><td>"+x[i].address+ "</td><td>"+x[i].email+ "</td>"+"<td> <img width='80px' height='60px' src="+'{{asset('uploads/members/profile')}}'+'/'+x[i].photo+ "> </td>"+"<td> <img width='80px' height='60px' src="+'{{asset('uploads/members/nid')}}'+'/'+x[i].nid_photo+ "> </td><td> <a class='btn btn-warning btn-sm btn-edit' href="+'{{url('admin/editdata')}}'+'/'+x[i].id+"><i class='fas fa-edit'></i></i></a> <button class='btn btn-danger btn-sm btn-del' data-sid="+x[i].id+"><i class='fas fa-trash'></i></i></button> </td></tr>";
					   
				}
                $("#tbody").html(output);
                },
                error:function(error){
                    
                }
            });
        }
        
        showData();

            //delete function
        
        $("#tbody").on("click",".btn-del", function(){
			 console.log("clicked delete button");
			 let id= $(this).attr("data-sid");
             var my_url = base_url+"/admin/deletedata/"+id;
			// alert(my_url);     
               mythis = this;
          $.ajax({
              url:my_url,
              method: "GET",
              success: function(response){
				
				  if(response){
					 
                    Toast.fire({
                            type:'success',
                            title:'Deleted member successfully.',
                        });
					  $(mythis).closest("tr").fadeOut();
				  } 
				  else{
                        Toast.fire({
                            type:'error',
                            title:'Unable to delete.',
                        });
				  }
			  },
              error:function(error){

              }		  
		  });			   
		  });

        //   //edit function

        //   $("#tbody").on("click",".btn-edit", function(){
		// 	 console.log("clicked edit button");
		// 	 let id= $(this).attr("data-sid");
        //      var my_url = base_url+"/admin/editdata/"+id;
		// 	// alert(my_url);     
        //        mythis = this;
        //   $.ajax({
        //       url:my_url,
        //       method: "GET",
        //       success: function(response){

        //     //    console.log(response);
             
			
		// 	  },
        //       error:function(error){

        //       }		  
		//   });			   
		//   });

        });

     
        $(function() {
            $("#all-category").DataTable();
            //   $('#example2').DataTable({
            //     "paging": true,
            //     "lengthChange": false,
            //     "searching": false,
            //     "ordering": true,
            //     "info": true,
            //     "autoWidth": false,
            //   });
        });

    </script>
@endsection
