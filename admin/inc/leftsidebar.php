<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/<?php echo (empty($logged_user_image))?  "user2-160x160.jpg" :  'users/' . $logged_user_image ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="dashboard.php" class="d-block"><?php echo $logged_user_full_name ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="dashboard.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-header">Our Application</li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="users.php?do=manage" class="nav-link">
                  <i class="fa fa-bars nav-icon"></i>
                  <p>User list</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="users.php?do=add" class="nav-link">
                  <i class="fa fa-plus nav-icon"></i>
                  <p>Add New User</p>
                </a>
              </li>
              <?php if($logged_user_role == 3){ ?>
              <li class="nav-item">
                <a href="users.php?do=approval_requests" class="nav-link">
                  <i class="fa fa-user nav-icon"></i>
                  <p>Administration Requests</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Category Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="categories.php?do=manage" class="nav-link">
                  <i class="fa fa-bars nav-icon"></i>
                  <p>Category list</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="categories.php?do=add" class="nav-link">
                  <i class="fa fa-plus nav-icon"></i>
                  <p>Add New Category</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Post Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="posts.php?do=manage" class="nav-link">
                  <i class="fa fa-bars nav-icon"></i>
                  <p>Post list</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="posts.php?do=add" class="nav-link">
                  <i class="fa fa-plus nav-icon"></i>
                  <p>Add New Post</p>
                </a>
              </li>
            </ul>
          </li>

           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Comment Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="comments.php?do=manage" class="nav-link">
                  <i class="fa fa-bars nav-icon"></i>
                  <p>Comment list</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>