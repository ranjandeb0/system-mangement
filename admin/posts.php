<?php include "inc/header.php"?>


  <!-- Navbar -->
  <?php include "inc/topbar.php"?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include "inc/leftsidebar.php"?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Manage posts -->
    <?php 
      if(isset($_GET['do'])){
        $do = $_GET['do'];
      }
      else{
        $do = "manage";
      }

      if($do == "manage"){
    ?>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage all post</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">posts</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      
      <div class="card">
        <div class="card-header border-transparent">
          <h3 class="card-title">All posts list</h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table m-0 table-bordered user-table">
              <thead>
              <tr>
                <th>Sl.</th>
                <th>Image</th>
                <th>Post title</th>
                <th>Post content</th>
                <th>Post Category</th>
                <th>Author Name</th>
                <th>Date & Time</th>
                <th>Metatags</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                <?php
                  $sl = 0;
                  $sql = "SELECT * FROM posts";
                  $operation = mysqli_query($db, $sql);
                  while ($row = mysqli_fetch_assoc($operation)) {
                    $sl++;
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
                ?>
                    <tr>
                      <td>  <?php echo $sl ?>      </td>
                      <td>  
                        <img class="list-user-image" src="<?php 
                          if(!empty($image)){echo "../assets/img/posts/" . $image; }
                          else{ echo "dist/img/avatar5.png"; }
                        ?>" alt="0">  
                      </td>
                      <td>  <?php echo $post_title ?>    </td>
                      <td>  <?php echo $post_content; ?></td>
                      <td>  
                        <?php 
                          $sql1       = "SELECT cat_title AS 'cat_name' FROM categories WHERE cat_id='$category_id'";
                          $operation1 = mysqli_query($db, $sql1);
                          if(!$operation1){
                            die("MySqli Error: " . mysqli_error($db));
                          }
                          else{
                            $row        = mysqli_fetch_assoc($operation1);
                            extract($row);
                            echo $cat_name;
                          }
                        ?> 
                      </td>
                      <td>  
                        <?php
                          $sql1 = "SELECT fullname AS 'author_name' FROM users WHERE id='$author_id'";
                          $operation1 = mysqli_query($db, $sql1);
                          if(!$operation1){
                            die("MySqli Error: " . mysqli_error($db));
                          }
                          else{
                            $row        = mysqli_fetch_assoc($operation1);
                            extract($row);
                            echo $author_name;
                          }
                        ?>
                      </td>
                      <td>  <?php echo $post_datetime; ?>   </td>
                      <td>  <?php echo $metatags; ?>   </td>
                      <td>  
                        <?php
                          if($status == 0)
                            { echo "<span class='badge badge-danger'>inactive</span>"; }
                          else if($status == 1)
                            { echo "<span class='badge badge-success'>activated</span>";}
                        ?>
                      </td>
                      <td>
                        <ul class="actions">
                          <li>
                            <a <?php echo "href = 'posts.php?do=edit&uid=$post_id'";?> > <i class="fas fa-edit"></i> </a>
                          </li>
                          <li>
                            <a href="#" data-toggle="modal" data-target="#modal-default-<?php echo $post_id; ?>"> <i class="fas fa-trash"></i> </a>
                          </li>
                        </ul>
                      </td>
                    </tr>

                      <div class="modal fade" id="modal-default-<?php echo $post_id; ?>" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Are you sure?</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p>Do you want to delete <?php echo ($logged_user_id == $author_id)? "your post?" : $author_name . "'s post?" ?></p>
                            </div>
                            <div class="modal-footer justify-content-between">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              <a href="posts.php?do=delete&delid=<?php echo $post_id; ?>"><button type="button" class="btn btn-primary">Delete</button></a>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                <?php
                  }
                ?>
              
              
              </tbody>
            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <?php
      }
      // Add New User
      else if($do == "add"){
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Add new user</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">add</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
          <div class="card">
            <div class="card-body">
              <form action="posts.php?do=store" method="POST" enctype="multipart/form-data" >
                    <div class="form-group">
                      <label for="input-post-title">Post Title</label>
                      <input type="text" name="post_title" class="form-control" id="input-post-title" placeholder="Type post title here" required="required"  >
                    </div>

                    <div class="form-group">
                      <label for="input-post-content">Post Content</label>
                      <textarea name="post_content" rows="10" class="form-control" id="input-post-content" placeholder="Type post Content here" required="required"></textarea>
                    </div>

                    <div class="form-group">
                      <label for="input-post-cat">Post Category</label>
                      <select class="form-control" name="post_category" id="input-post-cat">
                        <?php 
                          SelectCatSubCat($db, "post");
                        ?>
                      </select>
                    </div>

                    <div class="form-row">
                      <div class="col-auto">
                        <div class="form-group">
                          <label for="input-post-image">Upload post image</label>
                          <input type="file" name="image" class="form-control-file" id="input-post-image">
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="input-metatags">Metatags</label>
                      <input type="text" name="metatags" class="form-control" id="input-metatags" placeholder="Type metatags here">
                    </div>

                    <div class="form-group">
                      <label for="input-post-status">Post Status</label>
                      <select class="form-control" name="status" id="input-post-status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                      </select>
                  </div>
                <button class="btn btn-info mt-3" name="create-post" type="submit">Add New Post</button>
              </form>
            </div>
          </div>
        </section>
        <!-- /.content -->
    <?php
      }

      else if($do == "store"){
        if(isset($_POST['create-post'])){
          $post_title     =   mysqli_real_escape_string($db, $_POST['post_title']);
          $post_content   =   mysqli_real_escape_string($db, $_POST['post_content']);
          $category_id    =   mysqli_real_escape_string($db, $_POST['post_category']);
          $metatags       =   mysqli_real_escape_string($db, $_POST['metatags']);
          $status         =   mysqli_real_escape_string($db, $_POST['status']);
          //uploaded file
          $image      =   $_FILES['image']['name'];



          if(empty($image)){
            $sql = "INSERT INTO posts (post_title, post_content, post_datetime, category_id, author_id, metatags,  status) VALUES ('$post_title', '$post_content', now(), '$category_id', '$logged_user_id', '$metatags', '$status')";
          }
          else{
            $image_tmp  =   $_FILES['image']['tmp_name'];
            
            $image_name =   "post-" . rand(0,9999999) . "-" . $image;
            
            move_uploaded_file($image_tmp, "../assets/img/posts/" . $image_name);

            $sql = "INSERT INTO posts (post_title, post_content, post_datetime, category_id, author_id, image, metatags,  status) VALUES ('$post_title', '$post_content', now(), '$category_id', '$logged_user_id', '$image_name', '$metatags', '$status')";
          }

          $res = mysqli_query($db, $sql);
          if($res){
            header("Location: posts.php");
          }
          else{
            die("Error: " . mysqli_error($db));
          }
        }
      }

      else if($do == "edit"){
        if(isset($_GET['uid'])){
          $updateID = $_GET['uid'];
          $sql = "SELECT * FROM posts WHERE post_id='$updateID'";
          $operation = mysqli_query($db, $sql);

          if(!$operation){
            die("MySqli Error: " . mysqli_error($db));
          }
          else{
            while($row = mysqli_fetch_array($operation)){
              $post_title     =   $row['post_title'];
              $post_content   =   $row['post_content'];
              $category_id    =   $row['category_id'];
              $author_id      =   $row['author_id'];
              $image          =   $row['image'];
              $metatags       =   $row['metatags'];
              $status         =   $row['status'];
    ?>

              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">Edit post</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="posts.php?do=manage">Posts</a></li>
                        <li class="breadcrumb-item active">edit</li>
                      </ol>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.container-fluid -->
              </div>
              <!-- /.content-header -->

              <!-- Main content -->
              <section class="content">
                <div class="card">
                  <div class="card-body">
                    <form novalidate action="posts.php?do=update" method="POST" enctype="multipart/form-data" >
                      <div class="form-group">
                        <label for="input-post-title">Post Title</label>
                        <input type="text" name="post_title" value="<?php echo $post_title; ?>" class="form-control" id="input-post-title" placeholder="Type post title here" required="required"  >
                      </div>

                      <div class="form-group">
                        <label for="input-post-content">Post Content</label>
                        <textarea name="post_content" rows="10" class="form-control" id="input-post-content" placeholder="Type post Content here" required="required"><?php echo $post_content; ?></textarea>
                      </div>

                      <div class="form-group">
                        <label for="input-post-cat">Post Category</label>
                        <select class="form-control" name="post_category" id="input-post-cat">
                          <?php 
                            SelectCatSubCat($db, "post");
                          ?>
                        </select>
                      </div>

                      <div class="form-row">
                        <div class="col-auto">
                          <div class="form-group">
                            <label for="input-post-image">Upload post image</label>
                            <input type="file" name="image" class="form-control-file" id="input-post-image">
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="input-metatags">Metatags</label>
                        <input type="text" name="metatags" value="<?php echo $metatags; ?>" class="form-control" id="input-metatags" placeholder="Type metatags here">
                      </div>

                      <div class="form-group">
                        <label for="input-post-status">User Status</label>
                        <select class="form-control" name="status" id="input-post-status">
                          <option value="1" <?php if($status == 1){echo "selected";}?>>Active</option>
                          <option value="0" <?php if($status == 0){echo "selected";}?>>Inactive</option>
                        </select>
                      </div>
                      <input type="hidden" name="updateID" value="<?php echo $updateID?>">
                      <button class="btn btn-info mt-3" name="update-post" type="submit">Udpdate Post</button>
                    </form>
                  </div>
                </div>
              </section>
              <!-- /.content -->

    <?php
            }
          }
        }
      }
      else if($do == "update"){
        if (isset($_POST['update-post'])) {
          $updateID       =   mysqli_real_escape_string($db, $_POST['updateID']);
          $post_title     =   mysqli_real_escape_string($db, $_POST['post_title']);
          $post_content   =   mysqli_real_escape_string($db, $_POST['post_content']);
          $category_id    =   mysqli_real_escape_string($db, $_POST['post_category']);
          $metatags       =   mysqli_real_escape_string($db, $_POST['metatags']);
          $status         =   mysqli_real_escape_string($db, $_POST['status']);
          //uploaded file
          $image          =   mysqli_real_escape_string($db, $_FILES['image']['name']);

          
         if(empty($image)){
            $sql = "UPDATE posts SET post_title = '$post_title', post_content = '$post_content', category_id = '$category_id', metatags = '$metatags',  status = '$status' WHERE post_id = '$updateID'";
          }
          else{
            // Deleting old image
            $sql = "SELECT image FROM posts WHERE post_id = '$updateID'";
            $operation = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($operation);
            $oldImage = $row['image'];
            

            $image_tmp  =   $_FILES['image']['tmp_name'];
            
            $image_name =   "post-" . rand(0,9999999) . "-" . $image;
            
            move_uploaded_file($image_tmp, "../assets/img/posts/" . $image_name);

            $sql = "UPDATE posts SET post_title = '$post_title', post_content = '$post_content', category_id' = $category_id', image = '$image_name', metatags = '$metatags',  status = '$status' WHERE post_id = '$updateID'";
          }

          $res = mysqli_query($db, $sql);
          if($res){
            if(!empty($image)){
              if(!empty($oldImage)){
                unlink("../assets/img/posts/" . $oldImage);
              }
            }
            
            header("Location: posts.php?do=manage");
          }
          else{
            die("Error: " . mysqli_error($db));
          } 
        }
        else{
          header("Location: posts.php?do=manage");
        }
      }

      else if($do == "delete"){
        if(isset($_GET['delid'])){
          $deleteID = $_GET['delid'];

          $sql              =   "SELECT * FROM posts WHERE post_id ='$deleteID'";
          $res              =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          $row              =   mysqli_fetch_assoc($res);
          if($row['role'] == 3 && $logged_user_role != 3){
            die("You are out of your boundary.");
          }

          // Deleting image
          $oldImage = $row['image'];
          

          // Deleting User Data from Database
          $sql = "DELETE FROM posts WHERE post_id = '$deleteID'";
          $deleteOperation = mysqli_query($db, $sql);
          if($deleteOperation){
            if(!empty($oldImage)){
              unlink("../assets/img/posts/" . $oldImage);
            }
            header("Location: posts.php");
          }
          else{
            die("Error: " . mysqli_error($db));
          }
        }
      }
    ?>
   
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <?php include "inc/footer.php"?>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include "inc/scripts.php";?>
</body>
</html>