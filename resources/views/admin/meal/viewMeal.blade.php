@extends('admin.master')

@section('heading', 'All Meals')
@section('title', 'view meals')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                <div class="card-header col-md-6 col-6">
                    <h3>View Meal</h3>
                </div>
                <div class="card-header col-md-6 col-6 text-right">
                    <a href="{{route('admin.add.meal')}}" class="viewall"><i class="fas fa-hamburger"></i> Add Meal</a>
                </div>
            </div>
                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th> Member's Name</th>
                                <th> Total Meal</th>
                                <th>Month</th>
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
        function showMeal(){
            
            $.ajax({
                url:"{{route('admin.showMeal')}}",
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
				       "</td><td>"+x[i].full_name+ "</td><td>"+x[i].total_meal+ "</td><td>"+x[i].month+ "</td>"+"<td> <a class='btn btn-info btn-sm btn-edit' href="+'{{url('admin/mealDetails')}}'+'/'+x[i].members_id+"><i class='fas fa-eye'></i></i></a> </td></tr>";
					   
				}
                $("#tbody").html(output);
                },
                error:function(error){
                    
                }
            });
        }
        
        showMeal();

            //delete function
        
        $("#tbody").on("click",".btn-del", function(){
			 console.log("clicked delete button");
			 let id= $(this).attr("data-mid");
             var my_url = base_url+"/admin/deleteMeal/"+id;
			// alert(my_url);     
               mythis = this;
          $.ajax({
              url:my_url,
              method: "GET",
              success: function(response){
				
				  if(response){
					 
                    Toast.fire({
                            type:'success',
                            title:'Deleted meal successfully.',
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
