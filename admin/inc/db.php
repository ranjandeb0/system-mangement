<?php
	$db = mysqli_connect("localhost", "root", "", "system_management");
	if($db){
		echo "<!-- Database Connection SuccessFool -->";
	}
	else{
		die("Database Connection Failed" . mysqli_error($db));
	}
?>