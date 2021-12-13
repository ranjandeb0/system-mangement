<?php
	ob_start();
	session_start();
	include "../admin/inc/db.php";
	include "../admin/inc/functions.php";
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


	$pid 	= $_POST['id'];
	$type 	= $_POST['type'];

	if($type == "comment"){
		if(!empty($_POST['singleComment'])){
			if(!empty($_POST['fromBefore'])){
				readAndPrintComments($db, $pid, false, 3, $_POST['singleComment'], $_POST['fromBefore']);
			}
			else{
				readAndPrintComments($db, $pid, false, 3, $_POST['singleComment']);
			}
		}
		else{
			if(!empty($_POST['fromBefore'])){
				readAndPrintComments($db, $pid, false, 3, 0, $_POST['fromBefore']);
			}
			else{
				readAndPrintComments($db, $pid);
			}
		}
	}
	else if($type == "reply"){
		echo "<div style='display:none' class='reply-container ps-4 border-start'>";
		readAndPrintComments($db, $pid, true);
		echo "</div>";
	}
	else if($type == "gen_comment"){
		generateCommentForm($pid);
	}
	else if($type == "post_comment"){
		$comment_text 	= $_POST['comment_text'];
		if(!empty(trim($comment_text))){
			$comment_text 	= 	mysqli_real_escape_string($db, $comment_text);
			$logged_user_id = 	mysqli_real_escape_string($db, $logged_user_id);
			$pid 			= 	mysqli_real_escape_string($db, $pid);
			$sql = "INSERT INTO comments (author_id, comment, post_id, status, date_time) VALUES ('$logged_user_id', '$comment_text', '$pid', '1', now())";

			$res = mysqli_query($db, $sql);

			if(!$res){
				die("Error: " . mysqli_error($db));
			}
			$lastID = $db->insert_id;
			echo $lastID;
		}
	}
	else if($type == "edit_comment"){
		$pid 			= 	mysqli_real_escape_string($db, $pid);
		$comment_text 	= 	$_POST['comment_text'];
		if(isset($_POST['comment_text']) && !empty(trim($comment_text))){
			$comment_text 	= 	mysqli_real_escape_string($db, $comment_text);
			$sql = "SELECT author_id, comment FROM comments WHERE id = '$pid'";
			$res = mysqli_query($db, $sql);
			if(!$res){
				die("Error: " . mysqli_error($db));
			}else{
				$row = mysqli_fetch_assoc($res);
				$author_id = $row['author_id'];
				$old_comment = $row['comment'];
				if($author_id == $logged_user_id && $old_comment != $comment_text){
					$sql = "UPDATE comments SET comment = '$comment_text' WHERE id = '$pid'";
					$res = mysqli_query($db, $sql);
					if(!$res){
						die("Error: " . mysqli_error($db));
					}
				}else{
					echo "authority error";
				}
			}
		}
	}
	else if($type == "delete"){
		$pid 			= 	mysqli_real_escape_string($db, $pid);
		
		$sql = "SELECT author_id FROM comments WHERE id = '$pid'";
		$res = mysqli_query($db, $sql);
		if(!$res){
			die("Error: " . mysqli_error($db));
		}else{
			$row = mysqli_fetch_assoc($res);
			$author_id = $row['author_id'];
			if($author_id == $logged_user_id){
				$sql = "DELETE FROM comments WHERE id = '$pid'";
				$res = mysqli_query($db, $sql);
				if(!$res){
					die("Error: " . mysqli_error($db));
				}
			}else{
				echo "authority error";
			}
		}
	}
	ob_end_flush();
?>