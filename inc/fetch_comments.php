<?php
	include "../admin/inc/db.php";
	include "../admin/inc/functions.php";

	$pid 	= $_POST['id'];
	$type 	= $_POST['type'];

	if($type == "comment"){
		readAndPrintComments($db, $pid);
	}
	else if($type == "reply"){
		echo "<div style='display:none' class='reply-container ps-4 border-start'>";
		readAndPrintComments($db, $pid, true);
		echo "</div>";
	}

?>