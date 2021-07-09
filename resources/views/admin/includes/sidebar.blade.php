
<aside class="main-sidebar sidebar-light-olive elevation-5">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="mb-3">
      <img src="{{asset('assets/admin/dist/img/projectlogo.PNG')}}" alt="MMS" class="brand-image img-square elevation-0 ml-4"
           style="opacity: .8">
     
    </a>
    <hr>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-1 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('uploads/members/profile/' . session()->get('profile')) }}" class="img-circle elevation-2" alt="User Image" style="height:38px;width:38px; border-radius:50%;">
        </div>
        <div class="info">
          <a href="{{route('admin.view.profile')}}" class="d-block">{{session()->get('member_name')}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="{{route('admin.dashboard')}}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }} ">
              <i class="nav-icon fas fa-tachometer-alt text-secondary"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/member/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-users text-success"></i>
              <p>
                Manage Members
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add-member')}}" class="nav-link {{ request()->is('admin/member/addmembers','admin/member/editdata/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Member</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view-member')}}" class="nav-link {{ request()->is('admin/member/viewmembers') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Member</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/category/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-list text-cyan"></i>
              <p>
                Food Category
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add.category')}}" class="nav-link {{ request()->is('admin/category/addCategory','admin/category/editCategory/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view.category')}}" class="nav-link {{ request()->is('admin/category/viewCategory') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Category</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/food/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-hotdog text-pink"></i>
              <p>
                Food Item
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add.foodItem')}}" class="nav-link {{ request()->is('admin/food/addFoodItem','admin/food/editFoodItem/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Food Item</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view.foodItem')}}" class="nav-link {{ request()->is('admin/food/viewFoodItem') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Food Item</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/meal/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-hamburger text-orange"></i>
              <p>
                Manage Meal
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add.meal')}}" class="nav-link {{ request()->is('admin/meal/addMeal','admin/meal/editMeal/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Meal</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view.meal')}}" class="nav-link {{ request()->is('admin/meal/viewMeal','admin/meal/mealDetails/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Meal</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/expanse/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt text-lightblue"></i>
              <p>
                Manage Expanses
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add.expanse')}}" class="nav-link {{ request()->is('admin/expanse/addExpanse','admin/expanse/editExpanse/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Expanse</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view.expanse')}}" class="nav-link {{ request()->is('admin/expanse/viewExpanse','admin/expanse/detailsExpanse/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Expanse</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ request()->is('admin/payment/*') ? 'menu-open' : '' }}">
            <a href="javascript:void()" class="nav-link">
              <i class="nav-icon fas fa-comment-dollar text-lime"></i>
              <p>
                Manage Payments
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('admin.add.payment')}}" class="nav-link {{ request()->is('admin/payment/addPayment','admin/payment/editPayment/*') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Payment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.view.payment')}}" class="nav-link {{ request()->is('admin/payment/viewPayment') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Payment</p>
                </a>
              </li>
            </ul>
          </li>

          {{-- <li class="nav-item has-treeview">
            <a href="{{route('admin.total.summary')}}" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
               Meal Summary
              </p>
            </a>
          </li> --}}

          <li class="nav-item has-treeview">
            <a href="{{route('admin.total.report')}}" class="nav-link {{ request()->is('admin/totalReports','admin/viewReports') ? 'active' : '' }} ">
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