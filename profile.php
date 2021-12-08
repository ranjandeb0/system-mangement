<?php include "inc/head.php"?>
<?php include "inc/header.php"?>

<!-- Body Content -->
<div class="container-fluid">
	<div class="row body-row">
		<div class="col-lg-2 p-0">
			<?php  include "inc/leftsidebar.php"?>
		</div>
		<div class="offset-lg-1 col-lg-7 feed">
			<!-- Create Post Section -->
			<section class="create-post mt-5">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="card mb-4">
								<div class="card-body">
									<form action="" method="POST" enctype="multipart/form-data" >
										<div class="row">
											<div class="col-md-6">
												<div class="form-group mb-3">
							                      <label for="input-post-title" class="form-label">Post Title</label>
							                      <input type="text" name="post_title" class="form-control" id="input-post-title" placeholder="Type post title here" required="required"  >
							                    </div>

							                    <div class="form-group mb-3">
							                      <label for="input-post-content" class="form-label">Post Content</label>
							                      <textarea name="post_content" rows="7" class="form-control" id="input-post-content" placeholder="Type post Content here" required="required"></textarea>
							                    </div>
											</div>
											<div class="col-md-6">
												<div class="form-group mb-3">
							                      <label for="input-post-cat" class="form-label">Post Category</label>
							                      <select class="form-control" name="post_category" id="input-post-cat">
							                        <?php 
							                        	SelectCatSubCat($db, "post");
							                        ?>
							                      </select>
							                    </div>

							                    
						                        <div class="form-group mb-3">
						                          <label for="input-post-image" class="form-label">Upload post image</label>
						                          <input class="form-control" name="image" type="file" id="input-post-image">
						                   		</div>

							                    <div class="form-group mb-3">
							                      <label for="input-metatags" class="form-label">Metatags</label>
							                      <input type="text" name="metatags" class="form-control" id="input-metatags" placeholder="Type metatags here">
							                    </div>

							                    
							                  <button class="btn btn-info" name="create-post" type="submit">Create Post</button>
											</div>
										</div>
					              </form>
					              <?php 
					              	if(isset($_POST['create-post'])){
					              		uploadPost('post_title', 'post_content', 'post_category', 'metatags', null, 'image');
					              	}
					              ?>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</section>

			<section class="read-post">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<?php
								if(isset($_GET['cat'])){
									$res = readpost($db, $_GET['cat']);
								}
								else{
									$res = readpost($db);
								}
								if(mysqli_num_rows($res) > 0){
									while($row = mysqli_fetch_assoc($res)){
					                    $post_id        =   $row['post_id'];
					                    $post_title     =   $row['post_title'];
					                    $post_content   =   $row['post_content'];
					                    $datetime       =   date_create($row['post_datetime']);
					                    $post_datetime  =   date_format($datetime,"d/m/Y - h:ia");
					                    $category_id    =   $row['category_id'];
					                    $author_id      =   $row['author_id'];
					                    $image          =   $row['image'];
					                    $metatags       =   $row['metatags'];
					                    $status         =   $row['status'];

					                    $sql       = "SELECT cat_title FROM categories WHERE cat_id='$category_id'";
										$res1 = mysqli_query($db, $sql);
										if(!$res1){
											die("MySqli Error: " . mysqli_error($db));
										}
										else{
											$row        = mysqli_fetch_assoc($res1);
											$cat_name	= $row['cat_title'];
	                          			}
								?>
								<div class="card post mb-4">
									<?php if (!empty($image)): ?>
									<div class="post-img" >
										<div class="post-bg-img" style="background-image: url('assets/img/posts/<?php echo $image ?>');"></div>
										<img src="assets/img/posts/<?php echo $image ?>">
									</div>
									<?php endif ?>

									<div class="post-title-box">
										<h4 class="post-title mt-4 mb-0"><?php echo $post_title; ?></h4>
									</div>
									<div class="post-author-details mb-3">
										<?php 
				                          $sql  = "SELECT * FROM users WHERE id='$author_id'";
				                          $res1 	= mysqli_query($db, $sql);
				                          if(!$res1){
				                            die("MySqli Error: " . mysqli_error($db));
				                          }
				                          else{
				                            $row   = mysqli_fetch_assoc($res1);
				                            $author_name 	= $row['fullname'];
				                            $author_image 	= $row['image'];
				                          }
				                        ?>
				                        <div class="post-date-time">
											<span class="text-warning "><?php echo $cat_name ?></span>
											<p class="m-0 text-warning d-inline"> - <?php echo $post_datetime; ?></p>
											<h6 class="m-0 text-warning d-inline"> - <?php echo $author_name ?></h6>
										</div>
										
										<?php if($logged_user_id == $author_id){ ?>
											<div class="user-image ms-auto">
												<img src="admin/dist/img/users/<?php echo (empty($author_image))? 'default-img.png' : $author_image ;?>" alt = "author image">	
											</div>
										<?php } ?>
									</div>
									<div class="post-content">
										<p><?php echo $post_content?></p>
									</div>
									<div class="likes">
										<p class="m-0 py-2 text-info"> 13 People Likes this</p>
									</div>
									<div class="actions">
										<ul>
											<li>Like</li>
											<li>Comment</li>
											<li>Follow</li>
										</ul>
									</div>
								</div>
							<?php
									}
								}
								else{
							?>
									<div class="alert alert-info">No Available Posts to Show.</div>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<?php include "inc/footer.php"?>	