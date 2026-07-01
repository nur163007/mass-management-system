 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" role="button" aria-label="Profile menu">
            <img src="{{ asset('uploads/members/profile/' . session()->get('profile')) }}" class="img-circle elevation-2" style="height:38px;width:38px;" alt="User Image">
            <span class="d-none d-md-inline ml-2 text-truncate" style="max-width: 140px;">{{ session()->get('member_name') }}</span>
            <i class="fas fa-angle-down text-info ml-1"></i>
        </a>
     <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
        <a href="{{route('user.viewProfile')}}" class="dropdown-item">
            <i class="fas fa-user mr-2 text-info"></i> My Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('user_logout') }}" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout
        </a>
     </div>
      </li>
    </ul>
  </nav>