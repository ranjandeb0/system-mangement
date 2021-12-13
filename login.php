<?php 
	ob_start();
	session_start();
	include "admin/inc/db.php";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nbook login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body class="login">
	<div class="container">
		<div class="row">
			<div class="col-sm-6 first-column">
				<h2>Login Here</h2>
				<form action="" method="POST">
					<div class="form-group">
						<label class="form-label" for="input-user">Username or Email Address</label>
						<input type="text" class="form-control" name="user" id="input-user" placeholder="Type in your username or email address" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label class="form-label" for="input-password">Password</label>
						<input style="cursor: text;background-color: #fff;" type="password" onfocus="this.removeAttribute('readonly');" readonly name="password" class="form-control" id="input-password" placeholder="Type in your Password" required>
					</div>
					<input type="submit" value="Sign in" onclick="password.focus()"; class="btn btn-primary" name="login">
				</form>
				<a href="index.php"><p>Forgot password?</p></a>
				<?php
					if(isset($_POST['login'])){
						$user 		= 	$_POST['user'];
						$password 	= 	sha1($_POST['password']);
						$sql 		= 	"SELECT * FROM users WHERE username = '$user' OR email = '$user'";
						$res 		= 	mysqli_query($db, $sql);

						if(mysqli_num_rows($res) > 0){
							while($row = mysqli_fetch_assoc($res)){
								if($password == $row['password']){
									$_SESSION['id'] = $row['id'];
              						header('Location: index');
								}
								else{
									echo "<p class='text-danger'>Wrong password.</p>";
								}
							}
						}
						else{
							echo "<p class='text-danger'>The provided user name or email address is not registered yet. To register now <a class='link-info' href='#'>Sign Up here</a></p>";
						}
					}
				?>
			</div>

			<div class="col-sm-6 second-column">
				<div class="content">
					<figure class="text-center">
					  <blockquote class="blockquote">
					    <p>In this era, the so called information era, truth seekers are facing the biggest problem, thus we don't cloud what actually is and is not and we try to be the mirror of the actual present.</p>
					  </blockquote>
					  <figcaption class="blockquote-footer">
					    NBook <cite title="Source Title">Free Social Platform</cite>
					  </figcaption>
					</figure>
					<button class="btn btn-primary">Sign Up</button>
				</div>
			</div>
		</div>
	</div>
	<?php ob_end_flush(); ?>
</body>
</html>