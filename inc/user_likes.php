<?php 
	ob_start();
	session_start();
	include "../admin/inc/db.php";
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
		  $loggedIn = false;
		  $logged_user_id   =   null;
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

	if(isset($_POST['task'])){
		$task 	= 	mysqli_real_escape_string($db, $_POST['task']);
	}else{
		$task 	= 	"react";
	}

	if(isset($_POST['type'])){
		$type 	= 	mysqli_real_escape_string($db, $_POST['type']);
	}else{
		$type 	= 	"post";
	}

	if(isset($_POST['reactType'])){
		$reaction_type 	= 	mysqli_real_escape_string($db, $_POST['reactType']);
	}else{
		$reaction_type 	= 	1;
	}


	if($type == "post"){
		$post_id 	= 	mysqli_real_escape_string($db, $_POST['postData']);

		if($task == "react"){
			$sql 	=	"SELECT id FROM post_reactions WHERE post_id = '$post_id' AND user_id = '$logged_user_id'";
			$res 	= 	mysqli_query($db, $sql);
			if(!$res){
				die("Error: " . mysqli_error($db));
			}
			else{
				if (mysqli_num_rows($res) == 0) {
					$sql 	=	"INSERT INTO post_reactions (user_id, post_id, reaction_type) VALUES ('$logged_user_id', '$post_id', '$reaction_type')";
					$res 	=	mysqli_query($db, $sql);
					if(!$res){
						die("Error: " . mysqli_error($db));
					}else{
						echo "success";
					}
				}
				else{
					echo "already is";
				}
			}
		}
		else if($task == "undoReact"){
			$sql 	=	"SELECT id FROM post_reactions WHERE post_id = '$post_id' AND user_id = '$logged_user_id'";
			$res 	= 	mysqli_query($db, $sql);
			if(!$res){
				die("Error: " . mysqli_error($db));
			}
			else{
				if (mysqli_num_rows($res) != 0) {
					$sql 	=	"DELETE FROM post_reactions WHERE post_id = '$post_id' AND user_id = '$logged_user_id'";
					$res 	=	mysqli_query($db, $sql);
					if(!$res){
						die("Error: " . mysqli_error($db));
					}else{
						echo "success";
					}
				}
				else{
					echo "Does not exist";
				}
			}
		}
		else if($task == "changeReactType"){}
		else if($task == "countReact"){
			$sql 	=	"SELECT id FROM post_reactions WHERE post_id = '$post_id'";
			$res 	= 	mysqli_query($db, $sql);
			if(!$res){
				die("Error: " . mysqli_error($db));
			}
			else{
				echo mysqli_num_rows($res);
			}
		}
	}
	else if($type == "comment"){
		$comment_id 	= 	mysqli_real_escape_string($db, $_POST['commentData']);
	}

	ob_end_flush();
?>