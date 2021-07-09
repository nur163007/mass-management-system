 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
     
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
         <div class="input-group-append">
         
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Profile Dropdown Menu -->
      <li class="nav-item dropdown">
        <div class="nav-link" data-toggle="dropdown" href="#">
            <img src="{{ asset('uploads/members/profile/' . session()->get('profile')) }}" class="img-circle elevation-2" style="height:38px;width:38px;margin-top:-10px;"alt="User Image">
           
            <a href="#" class="d-inline m-1">{{session()->get('member_name')}} </a>
            
            <i class="fas fa-angle-down text-info"></i> 
        </div>
     <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
      
        <a href="{{route('user.viewProfile')}}" class="dropdown-item">
            <i class="fas fa-user mr-2 text-info"></i> My Profile
            
        </a>
        
        <div class="dropdown-divider"></div>
        <a href="{{ route('user_logout') }}"  class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout
        </a>
            
     </div>
           
      </li>
    </ul>
  </nav>