<?php include "inc/header.php"?>


  <!-- Navbar -->
  <?php include "inc/topbar.php"?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include "inc/leftsidebar.php"?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Manage Users -->
    <?php 
      if(isset($_GET['do'])){
        $do = $_GET['do'];
      }
      else{
        $do = "manage";
      }

      if($do == "manage"){
    ?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage all user</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">manage</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      
      <div class="card">
        <div class="card-header border-transparent">
          <h3 class="card-title">All users list</h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0 table-bordered user-table">
              <thead>
              <tr>
                <th>Sl.</th>
                <th>Full Name</th>
                <th>Image</th>
                <th>Username</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                <?php
                  $sl = 0;
                  $sql = "SELECT * FROM users";
                  $operation = mysqli_query($db, $sql);
                  while ($user = mysqli_fetch_assoc($operation)) {
                    $sl++;
                    $id       =   $user['id'];
                    $fullname =   $user['fullname'];
                    $username =   $user['username'];
                    $image    =   $user['image'];
                    $email    =   $user['email'];
                    $address  =   $user['address'];
                    $phone    =   $user['phone'];
                    $role     =   $user['role'];
                    $status   =   $user['status'];
                ?>
                    <tr <?php echo ($logged_user_id == $id)? "style = 'background: #3d464c'" : "" ?>>
                      <td>  <?php echo $sl ?>      </td>
                      <td>  <?php echo $fullname ?>    </td>
                      <td>  
                        <img class="list-user-image" src="<?php 
                          if(!empty($image)){echo "dist/img/users/" . $image; }
                          else{ echo "dist/img/avatar5.png"; }
                        ?>" alt="0">  
                      </td>
                      <td>  <?php echo $username; ?></td>
                      <td>  <?php echo $email; ?>   </td>
                      <td>  <?php echo $address; ?> </td>
                      <td>  <?php echo $phone; ?>   </td>
                      <td>  
                        <?php
                          if($role == 0)
                            { echo "<span class='badge badge-success'>admin</span>"; }
                          else if($role == 1)
                            { echo "<span class='badge badge-light'>user</span>";}
                          else if($role == 2)
                            { echo "<span class='badge badge-warning'>requested administration</span>";}
                          else if($role == 3)
                            { echo "<span class='badge badge-info'>master</span>";}
                        ?>
                      </td>
                      <td>  
                        <?php
                          if($status == 0)
                            { echo "<span class='badge badge-danger'>inactive</span>"; }
                          else if($status == 1)
                            { echo "<span class='badge badge-success'>activated</span>";}
                        ?>
                      </td>
                      <td>
                        <ul class="actions">
                          <li>
                            <a <?php echo ($role == 3 && $logged_user_role !=3)? "style = 'pointer-events: none;color: gray;' href = '#'" : "href = 'users.php?do=edit&uid=$id'";?> > <i class="fas fa-edit"></i> </a>
                          </li>
                          <li>
                            <a <?php echo ($role == 3 && $logged_user_role !=3)? "style = 'pointer-events: none;color: gray;'" : "";?> href="#" data-toggle="modal" data-target="#modal-default-<?php echo $id; ?>"> <i class="fas fa-trash"></i> </a>
                          </li>
                        </ul>
                      </td>
                    </tr>

                      <div class="modal fade" id="modal-default-<?php echo $id; ?>" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Are you sure?</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>Do you want to delete the data of <?php echo ($logged_user_id == $id)? "your account? You will be logged out immediately upon deleting your account." : "$username"?></p>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <a href="users.php?do=delete&delid=<?php echo $id; ?>"><button type="button" class="btn btn-primary">Delete</button></a>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                <?php
                  }
                ?>
              
              
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <?php
      }
      // Add New User
      else if($do == "add"){
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Add new user</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">add</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
          <div class="card">
            <div class="card-body">
              <form class="needs-validation add-form" novalidate action="users.php?do=store" method="POST" enctype="multipart/form-data" oninput='repassword.setCustomValidity(password.value != repassword.value ? "Passwords do not match." : "")'>
                <div class="form-row p-4">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="input-user-name">*User Name</label>
                      <input type="text" name="username" class="form-control" id="input-user-name" placeholder="Type full name here" required="required"  pattern="[a-zA-Z0-9_]+" >
                      <div class="invalid-feedback">
                        Please chose a unique username with no spaces, only (A-Z), (a-z), (0-9) and underscore
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="input-email-address">*Email Address</label>
                      <input type="email" name="email" class="form-control" id="input-email-address" placeholder="Type email adress here" required="required">
                      <div class="invalid-feedback">
                        Please choose a valid email address with @.
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="input-password">*Password</label>
                      <input type="text" name="password" class="form-control" id="input-password" placeholder="Type password here" required="required">
                      <div class="invalid-feedback">
                        Please choose a valid password.
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="re-input-password">*Confirm Password:</label>
                      <input type="text" name="repassword" class="form-control" id="re-input-password" placeholder="Re-type password here" required="required">
                      <div class="invalid-feedback">
                        Please re-enter password to confirm it.
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="col-auto">
                        <div class="form-group">
                          <label for="input-user-image">Upload user image</label>
                          <input type="file" name="image" class="form-control-file" id="input-user-image">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="input-first-name">*Full Name</label>
                      <div class="input-group">
                        <input type="text" name="first-name" class="form-control" id="input-first-name" placeholder="Type first name here" required="required">
                        <input type="text" name="last-name" class="form-control" id="input-last-name" placeholder="Type last name here" required="required">
                      </div>
                      <div class="invalid-feedback">
                        Please provide full name.
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="input-address">Address</label>
                      <input type="text" name="address" class="form-control" id="input-address" placeholder="Type address here">
                    </div>

                    <div class="form-group">
                      <label for="input-phone-number">Phone Number</label>
                      <input type="text" name="phone" class="form-control" id="input-phone-number" placeholder="Type phone number here">
                    </div>

                    <div class="form-group">
                      <label for="input-user-role">User Role</label>
                      <select class="form-control" name="role" id="input-user-role">
                        <option value="0" <?php echo ($logged_user_role == 3)? "" : "disabled"?>>Admin</option>
                        <option value="1" selected>User</option>
                        <option value="2">Request Administration</option>
                         <?php echo ($logged_user_role == 3)? "<option value='3'>Master</option>" : ""?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="input-user-status">User Status</label>
                      <select class="form-control" name="status" id="input-user-status">
                        <option value="0" selected>Inactive</option>
                        <option value="1">Active</option>
                      </select>
                    </div>
                  </div>
                <button class="btn btn-info mt-3" name="register-user" type="submit">Add New User</button>
                </div>
              </form>
            </div>
          </div>
        </section>
        <!-- /.content -->
    <?php
      }

      else if($do == "store"){
        if(isset($_POST['register-user'])){
          $username   =   $_POST['username'];
          $email      =   $_POST['email'];
          $password   =   $_POST['password'];
          $repassword =   $_POST['repassword'];
          $fullname   =   $_POST['first-name'] . " " . $_POST['last-name'];
          $address    =   $_POST['address'];
          $phone      =   $_POST['phone'];
          $role       =   $_POST['role'];
          $status     =   $_POST['status'];
          //uploaded file
          $image      =   $_FILES['image']['name'];


          $sql              =   "SELECT * FROM users WHERE (email ='$email' or username = '$username')";
          $res              =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          if(mysqli_num_rows($res)>0){
            $row = mysqli_fetch_array($res);
            if($row['username'] == $username){
              die("This username already exists in our database, go back to change it.". "<br />");
            }

            if($row['email'] == $email){
              die("This email address already exists in our database, go back to change it." . "<br />");
            }
          }

          if($password == $repassword){
            $hashed_password = sha1($password);

            if(empty($image)){
              $sql = "INSERT INTO users (username, email, password, fullname, phone, address, role, status, join_date) VALUES ('$username', '$email', '$hashed_password', '$fullname', '$phone', '$address', '$role', '$status', now())";
            }
            else{
              $image_tmp  =   $_FILES['image']['tmp_name'];
              
              if($role == 0 || $role == 2){
                $image_name =   "admin-" . rand(0,9999999) . "-" . $image;
              }
              else if($role == 1){
                $image_name =   "user-" . rand(0,9999999) . "-" . $image;
              }
              else if($role == 3){
                $image_name =   "master-" . rand(0,9999999) . "-" . $image;
              }
              else{
                $image_name =   "error-" . rand(0,9999999) . "-" . $image;
              }
              
              move_uploaded_file($image_tmp, "dist/img/users/" . $image_name);

              $sql = "INSERT INTO users (username, email, password, fullname, phone, address, role, status, image, join_date) VALUES ('$username', '$email', '$hashed_password', '$fullname', '$phone', '$address', '$role', '$status', '$image_name', now())";
            }

            if($role != 1 && $role != 2 && $logged_user_role != 3){
              die("You are out of your boundary.");
            }
            $userAddOperation = mysqli_query($db, $sql);
            if($userAddOperation){
              header("Location: users.php");
            }
            else{
              die("Error: " . mysqli_error($db));
            }
          }
          else{
            header("Location: users.php?do=add");
          }
        }
      }

      else if($do == "edit"){
        if(isset($_GET['uid'])){
          $updateID = $_GET['uid'];
          $sql = "SELECT * FROM users WHERE id='$updateID'";
          $operation = mysqli_query($db, $sql);

          if($operation){
            while($user = mysqli_fetch_array($operation)){
              $fullname     =   $user['fullname'];
              $image    =   $user['image'];
              $email    =   $user['email'];
              $address  =   $user['address'];
              $phone    =   $user['phone'];
              $role     =   $user['role'];
              $status   =   $user['status'];
    ?>

              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">Edit user information</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">add</li>
                      </ol>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.container-fluid -->
              </div>
              <!-- /.content-header -->

              <!-- Main content -->
              <section class="content">
                <div class="card">
                  <div class="card-body">
                    <form class="needs-validation add-form" novalidate action="users.php?do=update&uid=<?php echo $updateID?>" method="POST" enctype="multipart/form-data" oninput='repassword.setCustomValidity(password.value != repassword.value ? "Passwords do not match." : "")'>
                      <div class="form-row p-4">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="input-first-name">*Full Name</label>
                            <input type="text" name="full-name" class="form-control" id="input-first-name" placeholder="Type first name here" required="required" value="<?php echo $fullname; ?>">
                            <div class="invalid-feedback">
                              Please provide full name.
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="input-email-address">Email Address*</label>
                            <input type="email" name="email" class="form-control" id="input-email-address" placeholder="Type email adress here" required="required" value="<?php echo $email; ?>">
                            <div class="invalid-feedback">
                              Please choose a valid email address with @.
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="input-password">Password</label>
                            <input type="text" name="password" class="form-control" id="input-password" placeholder="Type password here">
                            <div class="invalid-feedback">
                              Please choose a valid password.
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="re-input-password">Confirm Password:</label>
                            <input type="text" name="repassword" class="form-control" id="re-input-password" placeholder="Re-type password here">
                            <div class="invalid-feedback">
                              Please re-enter password to confirm it.
                            </div>
                          </div>

                          <div class="form-row">
                            <div class="col-auto">
                              <div class="form-group">
                                <label for="input-user-image">Update user image</label><br>
                                <?php 
                                  if(!empty($image)){
                                    echo "<img class='list-user-image' src='dist/img/users/" . $image . "'>";
                                  }
                                  else{
                                    echo "No Picture Uploaded Yet!";
                                  }
                                ?>
                                <input type="file" name="image" class="form-control-file" id="input-user-image">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="input-address">Address</label>
                            <input type="text" name="address" class="form-control" id="input-address" placeholder="Type address here" value="<?php echo $address; ?>">
                          </div>

                          <div class="form-group">
                            <label for="input-phone-number" value="<?php echo $name; ?>">Phone Number</label>
                            <input type="text" name="phone" class="form-control" id="input-phone-number" placeholder="Type phone number here" value="<?php echo $phone; ?>">
                          </div>

                          <div class="form-group">
                            <label for="input-user-role">User Role</label>
                            <select class="form-control" name="role" id="input-user-role">
                              <option value="0" <?php echo ($logged_user_role == 3)? "" : "disabled"?> <?php if($role == 0){echo "selected";}?>>Admin</option>
                              <option value="1" <?php if($role == 1){echo "selected";}?>>User</option>
                              <option value="2" <?php if($role == 2){echo "selected";}?>>Request Administration</option>
                              <?php echo ($logged_user_role == 3)? "<option value='3' selected>Master</option>" : ""?>
                            </select>
                          </div>

                          <div class="form-group">
                            <label for="input-user-status">User Status</label>
                            <select class="form-control" name="status" id="input-user-status">
                              <option value="0" <?php if($status == 0){echo "selected";}?>>Inactive</option>
                              <option value="1" <?php if($status == 1){echo "selected";}?>>Active</option>
                            </select>
                          </div>
                        </div>
                        <button class="btn btn-info mt-3" name="update-user" type="submit">Update User</button>
                      </div>
                    </form>
                  </div>
                </div>
              </section>
              <!-- /.content -->

    <?php
            }
          }
        }
      }
      else if($do == "update"){
        if (isset($_POST['update-user']) && isset($_GET['uid'])) {
          $updateID   =   $_GET['uid'];
          $fullname   =   $_POST['full-name'];
          $email      =   $_POST['email'];
          $password   =   $_POST['password'];
          $repassword =   $_POST['repassword'];
          $address    =   $_POST['address'];
          $phone      =   $_POST['phone'];
          $role       =   $_POST['role'];
          $status     =   $_POST['status'];

          //uploaded file
          $image      =   $_FILES['image']['name'];

          // authority check
          if($role != 1 && $role != 2 && $logged_user_role != 3){
            die("You are out of your boundary.");
          }
          
          $sql              =   "SELECT * FROM users WHERE id ='$updateID'";
          $res              =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          $row              =   mysqli_fetch_assoc($res);
          if($row['role'] == 3 && $logged_user_role != 3){
            die("You are out of your boundary.");
          }
          if($email != $row['email']){
            $sql              =   "SELECT * FROM users WHERE email ='$email'";
            $res              =   mysqli_query($db, $sql);
            if(!$res){
              die("MySqli Error: " . mysqli_error($db));
            }
            if(mysqli_num_rows($res)>0){
              die("This Email Already Excits");
            }
          }

          if($password == $repassword){
            
            if(!empty($image)){
              $image_tmp  =   $_FILES['image']['tmp_name'];
              
              if($role == 0 || $role == 2){
                $image_name =   "admin-" . rand(0,9999999) . "-" . $image;
              }
              else if($role == 1){
                $image_name =   "user-" . rand(0,9999999) . "-" . $image;
              }
              else if($role == 3){
                $image_name =   "master-" . rand(0,9999999) . "-" . $image;
              }
              else{
                $image_name =   "error-" . rand(0,9999999) . "-" . $image;
              }
              
              move_uploaded_file($image_tmp, "dist/img/users/" . $image_name);

              // Deleting old image
              $sql = "SELECT image FROM users WHERE id = '$updateID'";
              $operation = mysqli_query($db, $sql);
              while($row = mysqli_fetch_array($operation)){
                $oldImage = $row['image'];
              }
              if(!empty($oldImage)){
                unlink("dist/img/users/" . $oldImage);
              }

              if(empty($password)){
                $sql = "UPDATE users SET fullname = '$fullname', email = '$email', image = '$image_name', phone = '$phone', address = '$address', role = '$role', status = '$status' WHERE id = '$updateID'";
              }
              else{
                $hashed_password = sha1($password);
                
                $sql = "UPDATE users SET fullname = '$fullname', email = '$email', password = '$hashed_password', image = '$image_name', phone = '$phone', address = '$address', role = '$role', status = '$status' WHERE id = '$updateID'";
              }
            }
            else{
               if(empty($password)){
                $sql = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', address = '$address', role = '$role', status = '$status' WHERE id = '$updateID'";
              }
              else{
                $hashed_password = sha1($password);
                
                $sql = "UPDATE users SET fullname = '$fullname', email = '$email', password = '$hashed_password', phone = '$phone', address = '$address', role = '$role', status = '$status' WHERE id = '$updateID'";
              }
            }
            

            $userUpdateOperation = mysqli_query($db, $sql);
            if($userUpdateOperation){
              header("Location: users.php");
            }
            else{
              die("Error: " . mysqli_error($db));
            }
          }
          else{
            header("Location: users.php");
          }
        }
        else{
          header("Location: users.php");
        }
      }
      else if($do == "delete"){
        if(isset($_GET['delid'])){
          $deleteID = $_GET['delid'];

          $sql              =   "SELECT * FROM users WHERE id ='$deleteID'";
          $res              =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          $row              =   mysqli_fetch_assoc($res);
          if($row['role'] == 3 && $logged_user_role != 3){
            die("You are out of your boundary.");
          }

          // Deleting image
          $oldImage = $row['image'];
          if(!empty($oldImage)){
            unlink("dist/img/users/" . $oldImage);
          }

          // Deleting User Data from Database
          $sql = "DELETE FROM users WHERE id = '$deleteID'";
          $deleteOperation = mysqli_query($db, $sql);
          if($deleteOperation){
            header("Location: users.php");
          }
          else{
            die("Error: " . mysqli_error($db));
          }
        }
      }
    
      else if($do == "approval_requests"){
        if($logged_user_role != 3){
          header("Location: users.php");
        }
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Manage all administration reqeusts</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">requests</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
          
          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title">All users list</h3>
            </div>

            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0 table-bordered user-table">
                  <thead>
                  <tr>
                    <th>Sl.</th>
                    <th>Full Name</th>
                    <th>Image</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sl = 0;
                      $sql = "SELECT * FROM users WHERE role = '2'";
                      $operation = mysqli_query($db, $sql);
                      
                      while ($user = mysqli_fetch_assoc($operation)) {
                        $sl++;
                        $id       =   $user['id'];
                        $fullname =   $user['fullname'];
                        $username =   $user['username'];
                        $image    =   $user['image'];
                        $email    =   $user['email'];
                        $address  =   $user['address'];
                        $phone    =   $user['phone'];
                        $role     =   $user['role'];
                        $status   =   $user['status'];
                    ?>
                        <tr>
                          <td>  <?php echo $sl ?>      </td>
                          <td>  <?php echo $fullname ?>    </td>
                          <td>  
                            <img class="list-user-image" src="<?php 
                              if(!empty($image)){echo "dist/img/users/" . $image; }
                              else{ echo "dist/img/avatar5.png"; }
                            ?>" alt="0">  
                          </td>
                          <td>  <?php echo $username; ?></td>
                          <td>  <?php echo $email; ?>   </td>
                          <td>  <?php echo $address; ?> </td>
                          <td>  <?php echo $phone; ?>   </td>
                          <td>  
                            <?php
                              if($status == 0)
                                { echo "<span class='badge badge-danger'>inactive</span>"; }
                              else if($status == 1)
                                { echo "<span class='badge badge-success'>activated</span>";}
                            ?>
                          </td>
                          <td style="width: 1%; white-space: nowrap;">
                            <ul class="actions">
                              <li>
                                <a href = "#" data-toggle="modal" data-target="#modal-default-<?php echo $id; ?>-approve"> <button class="btn btn-success">Approve</button> </a>
                              </li>
                              <li>
                                <a href="#" data-toggle="modal" data-target="#modal-default-<?php echo $id; ?>-reject"> <button class="btn btn-warning">Reject</button> </a>
                              </li>
                              <li>
                                <a href="#" data-toggle="modal" data-target="#modal-default-<?php echo $id; ?>-delete"> <button class="btn btn-danger">Delete</button> </a>
                              </li>
                            </ul>
                          </td>
                        </tr>


                          <div class="modal fade" id="modal-default-<?php echo $id; ?>-approve" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Are you sure?</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <p>Do you want to approve the request of <?php echo $username?>. This account will be upgraded as an administration account and given authorities to predefined extent.</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  <a href="users.php?do=approve&rid=<?php echo $id; ?>&approve=true"><button type="button" class="btn btn-primary">Approve</button></a>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>

                          <div class="modal fade" id="modal-default-<?php echo $id; ?>-reject" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Are you sure?</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <p>Do you want to reject the request of <?php echo $username?>. This account will continue as a normal user account.</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  <a href="users.php?do=approve&rid=<?php echo $id; ?>&approve=false"><button type="button" class="btn btn-primary">Reject</button></a>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>

                          <div class="modal fade" id="modal-default-<?php echo $id; ?>-delete" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Are you sure?</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <p>Do you want to delete the account of <?php echo $username?>. All data related to this account will be lost.</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                  <a href="users.php?do=approve&rid=<?php echo $id; ?>&approve=dump"><button type="button" class="btn btn-primary">Delete</button></a>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                          </div>
                    <?php
                      }
                      if (mysqli_num_rows($operation) == 0) {
                        echo "<tr><td colspan='100%' >No requests to show right now.</td></tr>";
                      }
                    ?>
                  
                  
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
          </div>
        </section>
        <!-- /.content -->

    <?php
      }
      else if($do == "approve"){
        if($logged_user_role != 3){
          header("Location: users.php");
        }
        if(isset($_GET['approve'])){
          if(isset($_GET['rid'])){
            $requestedID = $_GET['rid'];
          }

          if($_GET['approve'] == "true"){
            $sql = "UPDATE users SET role = '0' WHERE id='$requestedID'";
          }
          else if($_GET['approve'] == "false"){
            $sql = "UPDATE users SET role = '1' WHERE id='$requestedID'";
          }
          else if($_GET['approve'] == "dump"){
            $sql = "DELETE FROM users WHERE id='$requestedID'";
          }
          else{
            die("Error: wrong approval request");
          }

          $res = mysqli_query($db,$sql);
          if($res){
            $sql = "SELECT * FROM users WHERE role = '2'";
            $res = mysqli_query($db, $sql);
            if(mysqli_num_rows($res) > 0){
              header("Location: users.php?do=approval_requests");
            }
            else{
              header("Location: users.php");
            }
          }
          else{
            die("MySqli Error: " . mysqli_error($db));
          }
        }
      }
    ?>
   
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <?php include "inc/footer.php"?>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include "inc/scripts.php";?>
</body>
</html>