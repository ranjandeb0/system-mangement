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

	$type 	= $_POST['type'];
	
	if(!isset($_POST['id'])){
		$pid 	= 0;
	}else{
		$pid 	= mysqli_real_escape_string($db, $_POST['id']);
	}

	if(!isset($_POST['limit'])){
		$limit 	= 3;
	}else{
		$limit 	= $_POST['limit'];
	}

	if(isset($_POST['post_id'])){
		$post_id 		= 	mysqli_real_escape_string($db, $_POST['post_id']);
	}

	if($type == "comment"){
		if(isset($_POST['singleComment']) && !empty($_POST['singleComment'])){
			if(isset($_POST['fromBefore']) && !empty($_POST['fromBefore'])){
				readAndPrintComments($db, $pid, false, $limit, $_POST['singleComment'], $_POST['fromBefore']);
			}
			else{
				readAndPrintComments($db, $pid, false, $limit, $_POST['singleComment']);
			}
		}
		else{
			if(isset($_POST['fromBefore']) && !empty($_POST['fromBefore'])){
				readAndPrintComments($db, $pid, false, $limit, 0, $_POST['fromBefore']);
			}
			else{
				readAndPrintComments($db, $pid);
			}
		}
	}
	else if($type == "comment_count"){
		$sql = "SELECT id FROM comments WHERE post_id = '$pid' AND reply_of is null";
		$res = mysqli_query($db, $sql);
		if (!$res) {
			die("Error: " . mysqli_error($db));
		}else{
			echo mysqli_num_rows($res);
		}
	}
	else if($type == "latest_comment_count"){
		$sql = "SELECT id FROM comments WHERE post_id = '$post_id' AND id > '$pid' AND reply_of is null";
		$res = mysqli_query($db, $sql);
		if (!$res) {
			die("Error: " . mysqli_error($db));
		}else{
			echo mysqli_num_rows($res);
		}
	}
	else if($type == "reply"){
		echo "<div style='display:none' class='reply-container'>";
		readAndPrintComments($db, $pid, true, $limit);
		echo "</div>";
	}
	else if($type == "reply_count"){
		$sql = "SELECT id FROM comments WHERE post_id = '$pid' AND reply_of is not null";
		$res = mysqli_query($db, $sql);
		if (!$res) {
			die("Error: " . mysqli_error($db));
		}else{
			echo mysqli_num_rows($res);
		}
	}
	else if($type == "latest_reply"){
		$sql = "SELECT * FROM `comments` WHERE reply_of = '$post_id' and id > '$pid'";
		$res = mysqli_query($db, $sql);
		if (!$res) {
			die("Error: " . mysqli_error($db));
		}else{
			echo "<div class='loaded-reply' style='display:none'>";
			readAndPrintComments($db, $res);
			echo "</div>";
		}
	}
	else if($type == "latest_reply_count"){
		$sql = "SELECT id FROM `comments` WHERE reply_of = '$post_id' and id > '$pid'";
		$res = mysqli_query($db, $sql);
		if (!$res) {
			die("Error: " . mysqli_error($db));
		}else{
			echo mysqli_num_rows($res);
		}
	}
	else if($type == "gen_comment"){
		generateCommentForm();
	}
	else if($type == "post_comment"){
		$comment_text 	= $_POST['comment_text'];
		if(!empty(trim($comment_text))){
			$comment_text 	= 	mysqli_real_escape_string($db, $comment_text);
			$logged_user_id = 	mysqli_real_escape_string($db, $logged_user_id);
			$sql = "INSERT INTO comments (author_id, comment, post_id, status, date_time) VALUES ('$logged_user_id', '$comment_text', '$pid', '1', now())";

			$res = mysqli_query($db, $sql);

			if(!$res){
				die("Error: " . mysqli_error($db));
			}else{
				$lastID = $db->insert_id;
				echo $lastID;
			}
		}
	}
	else if($type == "post_reply"){
		$comment_text 	= $_POST['comment_text'];
		if(!empty(trim($comment_text))){
			$comment_text 	= 	mysqli_real_escape_string($db, $comment_text);
			$logged_user_id = 	mysqli_real_escape_string($db, $logged_user_id);
			$sql = "INSERT INTO comments (author_id, comment, post_id, reply_of, status, date_time) VALUES ('$logged_user_id', '$comment_text', '$post_id', '$pid', '1', now())";

			$res = mysqli_query($db, $sql);

			if(!$res){
				die("Error: " . mysqli_error($db));
			}else{
				$lastID = $db->insert_id;
				$sql = "SELECT * FROM comments WHERE id = '$lastID'";
				$res = mysqli_query($db, $sql);
				if(!$res){
					die("Error: " . mysqli_error($db));
				}else{
					readAndPrintComments($db, $res);
				}
			}
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