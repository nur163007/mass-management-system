
<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.includes.stylesheet')
@yield('custom_css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
   @if(session()->get('role') == 1 || session()->get('role') == 2)
 @include('admin.includes.header')
  @elseif(session()->get('role') == 3)
 @include('userpanel.includes.header')
 @endif
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
    @if(session()->get('role') == 1 || session()->get('role') == 2)
       @include('admin.includes.sidebar')
    @elseif(session()->get('role') == 3)
      @include('userpanel.includes.sidebar')
    @endif




  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-12">
            <ol class="breadcrumb float-sm-right mb-0">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active d-none d-sm-inline">@yield('heading')</li>
              <li class="breadcrumb-item active d-inline d-sm-none text-truncate" style="max-width: 55vw;">@yield('heading')</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
@yield('main-content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.2
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
@include('admin.includes.stylejs')
</body>
</html>
