@extends('admin.master')

@section('heading', 'All Categories')
@section('title', 'view category')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3>View Categories</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add.category')}}" class="viewall"><i class="fas fa-dolly-flatbed"></i> Add Categories</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th> Category Name</th>
                                <th>Item Photo</th>
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
        function showCategory(){
            $.ajax({
                url:"{{route('admin.showCategory')}}",
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
				       "</td><td>"+x[i].category_name+ "</td>"+"<td> <img width='80px' height='60px' src="+'{{asset('uploads/category')}}'+'/'+x[i].photo+ "> </td>"+"<td> <a class='btn btn-warning btn-sm btn-edit' href="+'{{url('admin/editCategory')}}'+'/'+x[i].id+"><i class='fas fa-edit'></i></i></a> <button class='btn btn-danger btn-sm btn-del' data-fid="+x[i].id+"><i class='fas fa-trash'></i></i></button> </td></tr>";
					   
				}
                $("#tbody").html(output);
                },
                error:function(error){
                    
                }
            });
        }
        
        showCategory();

            //delete function
        
        $("#tbody").on("click",".btn-del", function(){
			 console.log("clicked delete button");
			 let id= $(this).attr("data-fid");
             var my_url = base_url+"/admin/deleteCategory/"+id;
			// alert(my_url);     
               mythis = this;
          $.ajax({
              url:my_url,
              method: "GET",
              success: function(response){
				
				  if(response){
					 
                    Toast.fire({
                            type:'success',
                            title:'Deleted Category successfully.',
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
