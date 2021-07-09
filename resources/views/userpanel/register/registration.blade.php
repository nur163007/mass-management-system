<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Registration</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/sweetalert2/sweetalert2.min.css') }}">

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="bg-gradient-secondary">
<section class="content">
    <div class="container-fluid col-md-5 offset-md-3 mt-2">
        <!-- Small boxes (Stat box) -->
        
        <div class="card">
          
            <div class="card-header col-md-12 col-12 text-center">
                <h3 class="font-weight-bolder text-cyan">Mess Management System</h3>
                <h3 class="text-success">User Registration Form</h3>
            </div>
           
                 <div class="col-md-12 text-center mt-1">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block text-center">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger alert-block text-center">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ Session::get('error') }}</strong>
                        </div>
                    @endif
                </div>

            <div class="card-body text-dark">
                <form method="POST"  action="{{route('register.user')}}"enctype="multipart/form-data" id="form">
                 @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="full_name">Full Name</label>
                            <input class="form-control" type="text" id="full_name" name="full_name">
                            @if ($errors->has('full_name'))
                                <p class="text-danger">{{ $errors->first('full_name') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone_no">Phone No</label>
                            <input class="form-control" type="text" id="phone_no" name="phone_no">
                            @if ($errors->has('phone_no'))
                                <p class="text-danger">{{ $errors->first('phone_no') }}</p>
                            @endif
                        </div>

                         <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" id="email" name="email">
                            @if ($errors->has('email'))
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                         <div class="form-group col-md-6">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password">
                            @if ($errors->has('password'))
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                         <div class="form-group col-md-12">
                            <label for="address">Permanent Address</label>
                            <input class="form-control" type="text" id="address" name="address">
                            @if ($errors->has('address'))
                                <p class="text-danger">{{ $errors->first('address') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-6">
                            <label for="photo">Photo</label>
                            <input class="form-control-file" type="file" id="photo" name="photo">

                            @if ($errors->has('photo'))
                                <p class="text-danger">{{ $errors->first('photo') }}</p>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="nid_photo">NID Photo</label>
                            <input class="form-control-file" type="file" id="nid_photo" name="nid_photo">

                            @if ($errors->has('nid_photo'))
                                <p class="text-danger">{{ $errors->first('nid_photo') }}</p>
                            @endif
                        </div>
                    </div>
                    <input class="btn btn-success col-md-12 mt-2" type="submit" id="submit" name="submit" value="Register">
                    <div class="social-auth-links text-center">
                        <p>- OR -</p>
                      
                    </div>
                    <p class="text-center">
                      Already have an account?
                      <a href="{{url('/')}}" class="text-center text-cyan">LOGIN</a>
                   </p>
                </form>

            </div>
        </div>

        <!-- /.row -->

    </div><!-- /.container-fluid -->
</section>


{{-- data store with ajax --}}

<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/admin/dist/js/adminlte.min.js') }}"></script>
<script src="{{asset('assets/admin/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

</body>
</html>