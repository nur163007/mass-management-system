@extends('admin.master')

@section('heading', 'All members')
@section('title', 'view member')

@section('main-content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="card-header col-md-6 col-6">
                        <h3 class="font-weight-bolder">All Members</h3>
                    </div>
                    <div class="card-header col-md-6 col-6 text-right">
                        <a href="{{route('admin.add-member')}}" class="viewall"><i class="far fa-user"></i> Add Member</a>
                    </div>
                </div>

                @include('admin.includes.message')

                <div class="card-body">
                    <table id="all-category" class="table table-bordered table-hover">
                        <thead class="bg-cyan">
                            <tr>
                                <th>SL NO</th>
                                <th>Name</th>
                                <th>Phone no</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Photo</th>
                                <th>NID Photo</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                                                
                            @foreach ($members as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $member->full_name }}</td>
                               
                                <td> 0{{ $member->phone_no }}</td>
                                <td> {{ $member->email }}</td>
                                <td> {{ $member->address }}</td>
                                <td><img style="width: 60px; height:60px"
                                            src="{{ asset('uploads/members/profile/' . $member->photo) }}" alt=""></td>
                                <td><img style="width: 60px; height:60px"
                                            src="{{ asset('uploads/members/nid/' . $member->nid_photo) }}" alt=""></td>
                                
                                 <td> 
                                    <input type="checkbox" data-size="mini" data-toggle="toggle" data-on="Active" data-off="Inactive" id="memberStatus" data-id="{{ $member->id}}" {{ $member->status == 1 ? 'checked' : '' }} >
                                </td>
                                <td style="width: 80px">
                                    <a href="{{route('admin.edit-member',$member->id)}}" class="btn btn-info btn-xs"> <i class="fas fa-pencil-alt"></i> </a>
                                    <a href="{{route('admin.delete',$member->id)}}" class="btn btn-danger btn-xs"> <i class="fa fa-trash-alt"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                        
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
           $('body').on('change','#memberStatus',function(){
            //    alert('ok');
          var id=$(this).attr('data-id');
        //   alert(id);
          if(this.checked){
            var status = 1;
          }
          else{
            var status = 0;
          }
        //   console.log(status);
        $.ajax({
          url:'memberStatus/'+id+'/'+status,
          method:'get',
          success:function(success){
            console.log(success);
          },
        });

        });

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
