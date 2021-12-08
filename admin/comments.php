<?php include "inc/header.php"?>


  <!-- Navbar -->
  <?php include "inc/topbar.php"?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include "inc/leftsidebar.php"?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Manage Comments -->
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
            <h1 class="m-0">Manage all Comments</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">comments</li>
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
          <h3 class="card-title">All Comment list</h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
            <?php
              $dataArray = [
              'table'         =>  'comments', // Table name to print from
              'parent_by'     =>  'reply_of', // If there is parental system then the 'column' name of it
              'order_by'      =>  'id',  //  Order the rows by 'column'
              'order_type'    =>  'DESC', // Print something of parent comment
              'id'            =>  'id', // Name of id column of table
              'status_by'     =>  'status',
              'refer_by'      =>  'comment', //What you want to refer every single row of this table by
              'print_columns' => [
                'arr_serial'    => 'Sl.', //Use $sl as counter
                'author_id'     => 'Author Name',  //If you want any column to have custom task have column_do in parent array
                'comment'      => 'Comment', //Assign executable php code using column name variable as a string as it's value
                'reply_of'        => 'Reply Of',
                'status'        => 'Status',
                'date_time'     => 'Date & Time',
                'arr_action'    =>  'Action'
                ]
              ];
              adminDynamicListTable($dataArray, $db);
            ?>

            </table>
          </div>
          <!-- /.table-responsive -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <?php
      }
      else if($do == "edit"){
        if($logged_user_role != 3){
          header("Location: comments?do=manage");
        }
        if(isset($_GET['uid'])){
          $updateID = $_GET['uid'];
          $sql = "SELECT * FROM comments WHERE id='$updateID'";
          $res = mysqli_query($db, $sql);

          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          else{
            while($row = mysqli_fetch_array($res)){
              $author_id    =   $row['author_id'];
              $comment      =   $row['comment'];
              $post_id      =   $row['post_id'];
              $reply_of     =   $row['reply_of'];
              $status       =   $row['status'];
              $sql          =   "SELECT fullname FROM users WHERE id='$author_id'";
              $sub_res_user =   mysqli_query($db, $sql);
              $row          =   mysqli_fetch_assoc($sub_res_user);
              $author_name  =   $row['fullname'];
              $sql          =   "SELECT post_title, post_content,image FROM posts WHERE post_id='$post_id'";
              $sub_res_post =   mysqli_query($db, $sql);
              $row          =   mysqli_fetch_assoc($sub_res_post);
              $post_title   =   $row['post_title'];
              $post_content =   $row['post_content'];
              $image        =   $row['image'];
    ?>

              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">Edit Comment</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="comments?do=manage">comments</a></li>
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
                    <form action="comments.php?do=update" method="POST">
                      <div class="form-group">
                        <h5>Comment by <?php echo $author_name ?></h5>
                      </div>
                      <div class="form-group">
                        <h4>Post Title: <?php echo $post_title; ?></h4>
                      </div>

                      <div class="post-content mb-4">
                        <p class="m-0">post content:</p>
                        <?php  if(!empty($image)){echo "<img max-width='100%' max-height='350px' src='../assets/img/posts/{$image}' alt='post-img'"; } ?>
                        <p class="m-0"><?php echo $post_content; ?></p>
                      </div>

                      <div class="form-group">
                        <label for="input-comment-desc">Comment:</label>
                        <textarea name="comment" rows="3" class="form-control" id="input-comment-desc" placeholder="Type Comment here"><?php echo $comment ?></textarea>
                      </div>


                      <div class="form-group">
                        <label for="input-comments-status">Comment Status</label>
                        <select class="form-control" name="status" id="input-comment-status">
                              <option value="0" <?php if($status == 0){echo "selected";}?>>Inactive</option>
                              <option value="1" <?php if($status == 1){echo "selected";}?>>Active</option>
                        </select>
                      </div>
                    <input type="hidden" value="<?php echo $updateID?>" name="updateID">
                    <button class="btn btn-info mt-3" name="update-comment" type="submit">Update Comment</button>
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
        if (isset($_POST['update-comment'])) {
          $updateID     =   mysqli_real_escape_string($db, $_POST['updateID']);
          $comment      =   mysqli_real_escape_string($db, $_POST['comment']);
          $status       =   mysqli_real_escape_string($db, $_POST['status']);


          $sql = "UPDATE comments SET comment = '$comment', status = '$status' WHERE id ='$updateID'";
          
          $res          =   mysqli_query($db, $sql);
          if($res){
            header("Location: comments.php?do=manage");
          }
          else{
            die("Error: " . mysqli_error($db));
          }
        }
        else{
          header("Location: comments?do=manage");
        }
      }

      else if($do == "delete"){
        if(isset($_GET['delid'])){
          $deleteID = $_GET['delid'];

          // Deleting Child Comment
          $sql    =   "SELECT * FROM comments WHERE id ='$deleteID'";
          $res    =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          else{
            $row    =   mysqli_fetch_assoc($res);
            if($row['has_child']){
              $sql    =   "DELETE FROM categories WHERE parent_cat ='$deleteID'";
              $res    =   mysqli_query($db, $sql);
              if(!$res){
                die("MySqli Error: " . mysqli_error($db));
              }
            }
          }

          // Deleting comment Data from Database
          $sql = "DELETE FROM categories WHERE cat_id = '$deleteID'";
          $deleteOperation = mysqli_query($db, $sql);
          if($deleteOperation){
            header("Location: categories.php?do=manage");
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