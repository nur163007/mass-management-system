@extends('admin.master')

@section('heading', 'Admin Profile')
@section('title', 'profile')

@section('main-content')
   
	 <section class="content">
	    		  @include('admin.includes.message')
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <form action="{{route('admin.photo.change')}}" method="post"enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" id="photoId" name="photoId" value="{{$profile->id}}">
                <div class="text-center">

                  <img class="profile-user-img img-fluid img-circle" src="{{ asset('uploads/members/profile/' . $profile->photo) }}" alt="User profile picture" id="previewImage" style="height: 180px;width: 180px; border-radius: 50%;" required>
                
                </div>
                 <div class="text-right mr-5">
                    <label for="profileImage"><i class="fas fa-camera"></i></label>
                    <input type="file" name="photo" id="profileImage"onchange="displayImage(this)" style="display: none;visibility: none;">
                 </div>
                 <!-- <div id="preview"></div> -->

                <h3 class="profile-username text-center">{{ $profile->full_name}}</h3>


                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right">{{ $profile->email}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Phone</b> <a class="float-right">0{{ $profile->phone_no}}</a>
                  </li>
                </ul>

                <input type="submit" name="submit" value="Update" class="btn btn-primary btn-block">
               </form>
              </div>
           
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                <p class="text-muted">{{ $profile->address}}</p>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile Update</a></li>
                  <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password Update</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">

                <!-- profile panel start -->
                  <div class="tab-pane active" id="profile">
                    <form class="form-horizontal" action="{{route('admin.update.profile')}}" method="post">
                    	@csrf
                    	<input type="hidden" id="memberId" name="memberId" value="{{$profile->id}}">
                      <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="name" name="name" value="{{$profile->full_name}}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" id="email" name="email" value="{{$profile->email}}">
                        </div>
                      </div>
                    
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="phone" id="phone"value="0{{$profile->phone_no}}">
                        </div>
                      </div>

                        <div class="form-group row">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="address" id="address" value="{{$profile->address}}">
                        </div>
                      </div>
                   
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <input type="submit" class="btn btn-success" name="submit" value="Update">
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.profile panel end -->

                  <!-- password change panel start -->
                  <div class="tab-pane" id="password">
                    <form class="form-horizontal" action="{{route('admin.change.password')}}" method="post">
                    	@csrf
                    	<input type="hidden" id="mid" name="mID" value="{{$profile->id}}">
                      <div class="form-group row">
                        <label for="old_pass" class="col-sm-3 col-form-label">Current Password</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="old_pass" name="old_pass"placeholder="Enter your current password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="new_pass" class="col-sm-3 col-form-label">New Password</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Enter a new password" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="confirm_pass" class="col-sm-3 col-form-label">Confirm Password</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="Enter confirm password" required>
                        </div>
                      </div>
                   
                      <div class="form-group row">
                        <div class="offset-sm-3 col-sm-6">
                          <input type="submit" class="btn btn-success" name="submit" value="Change password">
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- password change panel end -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->


            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
	  
@endsection

@section('custom_js')
    <script>
        // var base_url = window.location.origin;
            //  SweetAlert2 
    const Toast = Swal.mixin({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        showConfirmbutton: false,
                        timer:3000
                    });

 function displayImage(e){

  if(e.files[0]){
      var reader = new FileReader();
      
      reader.onload = function(e){
        document.querySelector('#previewImage').setAttribute('src',e.target.result);
      }
      reader.readAsDataURL(e.files[0]);
    }
    
 }

    </script>
@endsection