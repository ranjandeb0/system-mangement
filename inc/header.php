<body>
	<header>
		<nav class="navbar navbar-expand-lg p-3 navbar-dark">
		  <div class="container-fluid">
		  	<!-- Logo -->
		    <a class="navbar-brand" href="#">NBook</a>

		    <!-- Responsive Navbar Button -->
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    	<?php if($loggedIn){ ?>
					<ul class="navbar-nav m-auto mb-2 mb-lg-0">
				        <li class="nav-item">
				          <a class="nav-link active" aria-current="page" href="index">Home</a>
				        </li>
				        <li class="nav-item">
				          <a class="nav-link" href="#">My Profile</a>
				        </li>
				        <li class="nav-item">
				          <a class="nav-link" href="#">Followers</a>
				        </li>
				        <li class="nav-item">
				          <a class="nav-link" href="#">My Follows</a>
				        </li>
				        <li class="nav-item">
				          <a class="nav-link" href="logout">Log Out</a>
				        </li>
			      	</ul>
				<?php } else { ?>
					<ul class="navbar-nav cat-nav m-auto mb-2 mb-lg-0">
						<li class="nav-item">
				          <a class="nav-link active" href="index">Home</a>
				        </li>
					<?php 
						DropDownCatSubCat($db);
			    	?>
				</ul>
				<?php } ?>
		      
		      <?php if($loggedIn){ ?>
		      <form class="d-flex">
		        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
		        <button class="btn btn-outline-success" type="submit">Search</button>
		      </form>
		      <?php } else { ?>
		      	<a href="login">
		      		<button class="btn btn-success">Log In</button>
		      	</a>
		      <?php } ?>

		    </div>
		  </div>
		</nav>
	</header>