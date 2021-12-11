<?php
	$db = mysqli_connect("localhost", "root", "", "system_management");
	if(!$db){
		die("Database Connection Failed" . mysqli_error($db));
	}
?>