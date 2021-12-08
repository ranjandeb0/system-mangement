<?php
  ob_start(); 
  session_start();
  include "inc/db.php";
  include "inc/functions.php";
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
      header("Location: index.php");
    }

    $row                      =   mysqli_fetch_assoc($res);
    $logged_user_full_name    =   $row['fullname'];
    $logged_user_role         =   $row['role'];
    $logged_user_status       =   $row['status'];
    $logged_user_image        =   $row['image'];

    if(!($logged_user_role == 0) && !($logged_user_role == 3)){
      header("Location: index");
    }
  }
  else{
    header("Location: index");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>System Management</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Custom css -->
  <link rel="stylesheet" href="dist/css/style.css">
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->