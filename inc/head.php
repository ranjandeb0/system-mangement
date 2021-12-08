<?php 
	ob_start();
	session_start();
	include "admin/inc/db.php";
  include "admin/inc/functions.php";
  $loggedIn = false;
	if(isset($_SESSION['id'])){
    $logged_user_id          =   $_SESSION['id'];
    $sql              =   "SELECT * FROM users WHERE id ='$logged_user_id'";
    $res              =   mysqli_query($db, $sql);

    if(!$res){
      die("MySqli Error: " . mysqli_error($db));
    }

    if(mysqli_num_rows($res) == 0){
      session_unset();
      session_destroy();
      header("Location: login.php");
    }
    else{
      $loggedIn = true;
      $row                      =   mysqli_fetch_assoc($res);
      $logged_user_full_name    =   $row['fullname'];
      $logged_user_role         =   $row['role'];
      $logged_user_status       =   $row['status'];
      $logged_user_image        =   $row['image'];
    }

    

  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nbook || Free Social Platform</title>

	<!-- Bootstrap CSS CDN link -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

	<!-- Custom CSS link -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>