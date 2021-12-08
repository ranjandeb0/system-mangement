<div class="left-side-bar">
	<div class="user-details">
		<div class="user-image">
			<img src="admin/dist/img/users/<?php echo (empty($logged_user_image))?  'default-img.png' :  $logged_user_image ?>"  alt="User Image">	
		</div>
		<div class="user-name">
			<h6><?php echo $logged_user_full_name;?></h6>
		</div>
	</div>
	<ul class="category-menu">
		<li class="nav-header">Category menu</li>
		<?php 
			$url = $_SERVER["REQUEST_URI"];
			if (strrpos($url, "profile")) {
				// code...
			}
			else{
				DropDownCatSubCat($db);
			}
        	
    	?>
	</ul>
	<ul class="recent-menu">
		<li class="nav-header">Recent Occurance</li>
		<li><a href="#">Recent Post</a></li>
		<li><a href="#">Recent Comment</a></li>
		<li><a href="#">Recent Follows</a></li>
	</ul>
</div>