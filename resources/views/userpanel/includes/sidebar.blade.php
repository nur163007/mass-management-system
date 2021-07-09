<aside class="main-sidebar sidebar-dark-olive elevation-5">
    <!-- Brand Logo -->
    <a href="{{route('user.dashboard')}}" class="mb-3">
      <img src="{{asset('assets/admin/dist/img/projectlogo.PNG')}}" alt="MMS" class="brand-image img-square elevation-0 ml-4"
           style="opacity: .8">
      
    </a>
        <hr>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-1 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('uploads/members/profile/' . session()->get('profile')) }}" class="img-circle elevation-2" alt="User Image"style="height:38px;width:38px; border-radius:50%;">
        </div>
        <div class="info">
           <a href="{{route('user.viewProfile')}}" class="d-block">{{session()->get('member_name')}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="{{route('user.dashboard')}}" class="nav-link {{ request()->is('user/dashboard') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-tachometer-alt text-secondary"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="{{route('user.viewProfile')}}" class="nav-link {{ request()->is('user/profile/*') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-user text-purple"></i>
              <p>
               View Profile
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="{{route('user.viewMeal')}}" class="nav-link {{ request()->is('user/meal/*') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-hamburger  text-orange"></i>
              <p>
               View Meals
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="{{route('user.viewPayment')}}" class="nav-link {{ request()->is('user/payment/*') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-comment-dollar text-lime"></i>
              <p>
               View Payments
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="{{route('user.viewExpanse')}}" class="nav-link {{ request()->is('user/expanse/*') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-money-bill-alt text-lightblue"></i>
              <p>
               View Expanses
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="{{route('user.viewReport')}}" class="nav-link {{ request()->is('user/report/*') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-database text-purple"></i>
              <p>
               Reports
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>