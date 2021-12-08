<?php
  ob_start();
  session_start();
  include "inc/db.php";

  if(isset($_SESSION['id'])){
    $logged_user_id   =   $_SESSION['id'];
    $sql              =   "SELECT * FROM users WHERE id ='$logged_user_id'";
    $res              =   mysqli_query($db, $sql);

    if(!$res){
      die("MySqli Error: " . mysqli_error($db));
    }

    if(mysqli_num_rows($res) == 0){
      session_unset();
      session_destroy();
      header("Location: index.php");
    }

    $row                      =   mysqli_fetch_assoc($res);
    $logged_user_role         =   $row['role'];
    if(($logged_user_role == 0) || ($logged_user_role == 3)){
      header("Location: dashboard");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Admin</b>LTE</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="text"  name="username" class="form-control" placeholder="Type your username or email address" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input style="cursor: text;background-color: #fff;" type="password" onfocus="this.removeAttribute('readonly');" readonly name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="sign-in" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <?php
        if(isset($_POST['sign-in'])){
          $user             = $_POST['username'];
          $password         = $_POST['password'];
          $hashed_password  = sha1($password);

          $sql = "SELECT * FROM users WHERE (email = '$user' or username = '$user')";
          $res = mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: ". mysqli_error($db));
          }
          if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);

            if($hashed_password == $row['password']){
              $_SESSION['id'] = $row['id'];
              header('Location: dashboard');
            }
            else{
              echo "Wrong password";
            }
            
          }
          else{
            echo "Username or email is not registered";
          }
        }
      ?>

      <p class="mb-1">
        <a href="forgot-password.php">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.php" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<?php ob_end_flush(); ?>
</body>
</html>
