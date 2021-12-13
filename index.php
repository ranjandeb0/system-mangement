<?php include "inc/head.php"?>
<?php include "inc/header.php"?>

<!-- Body Content -->
<div class="<?php echo ($loggedIn)? "container-fluid" : "container" ; ?>">
	<div class="row">
		<?php if(($loggedIn)){ ?>
			<div class="col-lg-2 p-0">
				<?php include "inc/leftsidebar.php"?>
			</div>
		<?php } ?>
		
		<div class="<?php echo ($loggedIn)? "offset-lg-1 col-lg-7" : "col-md-8" ; ?> feed">

			<?php if($loggedIn && !isset($_GET['cat'])){ ?>
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
			<?php } ?>

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
									if(isset($_GET['cat'])){
										$category_id = $_GET['cat'];
										$sql  	= "SELECT cat_title FROM categories WHERE cat_id='$category_id'";
										$title_res 	= mysqli_query($db, $sql);
										$title_row 	= mysqli_fetch_assoc($title_res);

										echo "<h3 class = 'alert alert-info mt-4'>Category: ". $title_row['cat_title'] ."</h3>";
									}
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

					                    $sql  = "SELECT * FROM comments WHERE reply_of is not null and post_id='$post_id' and status = '1'";
										$comment_res	= mysqli_query($db, $sql);
										if(!$comment_res){
											die("MySqli Error: " . mysqli_error($db));
										}
										else{
											$replies_count	=	mysqli_num_rows($comment_res);
										}

										$sql  = "SELECT * FROM comments WHERE reply_of is null and post_id='$post_id' and status = '1'";
										$comment_res	= mysqli_query($db, $sql);
										if(!$comment_res){
											die("MySqli Error: " . mysqli_error($db));
										}
										else{
											$comments_count	=	mysqli_num_rows($comment_res);
										}

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
										<div class="card post my-4 pb-4"  data="<?php echo $post_id ?>">
											<?php if (!empty($image)): ?>
											<div class="post-img mb-4" >
												<div class="post-bg-img" style="background-image: url('assets/img/posts/<?php echo $image ?>');"></div>
												<img src="assets/img/posts/<?php echo $image ?>">
											</div>
											<?php endif ?>

											<div class="post-title-box">
												<h4 class="post-title mb-0"><?php echo $post_title; ?></h4>
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
												
												<?php if($loggedIn && $logged_user_id == $author_id){ ?>
													<div class="user-image ms-auto">
														<img src="admin/dist/img/users/<?php echo (empty($author_image))? 'default-img.png' : $author_image ;?>" alt = "author image">	
													</div>
												<?php } ?>
											</div>
											<div class="post-content">
												<p><?php echo $post_content?></p>
											</div>
											<div class="post-info">
												<p class="m-0 text-info"> 13 People Likes this</p>
												<p class="m-0 text-info"> <?php echo "{$comments_count} comment". (($comments_count>1)? "s": "") ." & {$replies_count} ". (($replies_count>1)? "replies": "reply") ?></p>
											</div>
											<?php if($loggedIn){ ?>
													<div class="actions mb-2">
														<ul>
															<li class="like-btn">Like</li>
															<li class="comment-gen-btn show" data ="<?php echo $post_id ?>">Comment</li>
															<li class="follow-btn">Follow</li>
														</ul>
													</div>
													<?php 
												} 
												if ($comments_count>0) { ?>
													<div class="comment-container">
														<h5 class="comment-heading my-2 border-bottom">Comments</h5>
														<div class="comment-box px-2">
															<?php 
																readAndPrintComments($db, $post_id, false, 2); 
																if($comments_count>2){
																?>
																<span class="view-more-comments link-colored">See more comments</span>
															<?php } ?>
														</div>
													</div>
													<?php 
												}
												if(!$loggedIn){ 
													echo "<p class='text-comment-log mb-0'>To post comment you must log in.</p>";
											 	}
											 ?>

										</div>
									<?php
									}
								}
								else{
									echo "<div class='alert alert-info mt-4'>No Available Posts to Show.</div>";
								}
							?>
						</div>
					</div>
				</div>
			</section>
		</div>

		<?php if(!$loggedIn){ ?>
			<div class="col-md-4 ">
				<div class="right-side-bar mt-4">
					<form>
						<div class="input-group">
					        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
					        <button class="btn btn-outline-success" type="submit">Search</button>
						</div>
			    	</form>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<?php include "inc/footer.php"?>	