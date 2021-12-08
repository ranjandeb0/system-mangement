<?php include "inc/header.php"?>


  <!-- Navbar -->
  <?php include "inc/topbar.php"?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include "inc/leftsidebar.php"?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Manage Categories -->
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
            <h1 class="m-0">Manage all Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">categories</li>
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
          <h3 class="card-title">All Categories list</h3>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="table-responsive">
                <?php
                  $dataArray = [
                  'table'         =>  'categories', // Table name to print from
                  'parent_by'     =>  'parent_cat', // If there is parental system then the 'column' name of it
                  'order_by'      =>  'cat_title',  //  Order the rows by 'column'
                  'title'         =>  'cat_title', // Print something of parent category
                  'id'            =>  'cat_id', // Name of id column of table
                  'status_by'     =>  'status',
                  'refer_by'      =>  'category', //What you want to refer every single row of this table by
                  'print_columns' => [
                    'arr_serial'    => 'Sl.', //Use $sl as counter if ever wish to use custom task
                    'cat_title'     => 'Category title',  //If you want any column to have custom task have column_do in parent array
                    'cat_desc'      => 'Description cat', //Assign executable php code using column name variable as a string.. 
                    'parent_cat'    => 'Parent Category', //..as it's value using necessary escape character
                    'status'        => 'Status',
                    'arr_action'    =>  'Action'
                  ]
                ];
                  adminDynamicListTable($dataArray, $db);
                ?>
          </div>
          <!-- /.table-responsive -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <?php
      }
      // Add New Category
      else if($do == "add"){
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Add a new category</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="categories.php?do=manage">categories</a></li>
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
              <form action="categories.php?do=store" method="POST">
                  <div class="form-group">
                    <label for="input-category-name">Category Name</label>
                    <input type="text" name="cat_name" class="form-control" id="input-category-name" placeholder="Type category name here" required="required">
                  </div>

                  <div class="form-group">
                    <label for="input-category-desc">Category Description</label>
                    <textarea name="cat_desc" rows="3" class="form-control" id="input-category-desc" placeholder="Type Category Description here"></textarea>
                  </div>


                  <div class="form-group">
                    <label for="input-parent-cat">Parent Category</label>
                    <select class="form-control" name="parent_cat" id="input-parent-cat">
                      <option value="" selected>None</option>
                      <?php 
                        SelectCatSubCat($db, "add");
                      ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="input-category-status">Category Status</label>
                    <select class="form-control" name="status" id="input-category-status">
                      <option value="0">Inactive</option>
                      <option value="1" selected>Active</option>
                    </select>
                  </div>
                <button class="btn btn-info mt-3" name="add-category" type="submit">Add New Category</button>
              </form>
            </div>
          </div>
        </section>
        <!-- /.content -->
    <?php
      }

      else if($do == "store"){
        if(isset($_POST['add-category'])){
          $cat_name     =   mysqli_real_escape_string($db, $_POST['cat_name']);
          $cat_desc     =   mysqli_real_escape_string($db, $_POST['cat_desc']);
          $status       =   mysqli_real_escape_string($db, $_POST['status']);

          if(!empty($_POST['parent_cat'])){
            $parent_cat     =   mysqli_real_escape_string($db, $_POST['parent_cat']);
          }
          else{
            $parent_cat   =   $_POST['parent_cat'];
          }


          $sql              =   "SELECT * FROM categories WHERE cat_title = '$cat_name'";
          $res              =   mysqli_query($db, $sql);
          if(!$res){
            die("MySqli Error: " . mysqli_error($db));
          }
          if(mysqli_num_rows($res)>0){
            die("This category already exists in our database.". "<br />");
          }
          else{
            if(empty($parent_cat)){
              $sql = "INSERT INTO categories (cat_title, cat_desc, status, parent_cat) VALUES ('$cat_name', '$cat_desc', '$status', null)";
            }
            else{
              $sql = "INSERT INTO categories (cat_title, cat_desc, status, parent_cat) VALUES ('$cat_name', '$cat_desc', '$status', '$parent_cat')";
            }
            
            $res = mysqli_query($db, $sql);
            if($res){
              header("Location: categories.php?do=manage");
            }
            else{
              die("Error: " . mysqli_error($db));
            }
          }

          
        }
      }

      else if($do == "edit"){
        if(isset($_GET['uid'])){
          $updateID = $_GET['uid'];
          $sql = "SELECT * FROM categories WHERE cat_id='$updateID'";
          $operation = mysqli_query($db, $sql);

          if(!$operation){
            die("MySqli Error: " . mysqli_error($db));
          }
          else{
            while($row = mysqli_fetch_array($operation)){
              $cat_id       =   $row['cat_id'];
              $cat_title    =   $row['cat_title'];
              $cat_desc     =   $row['cat_desc'];
              $parent_cat   =   $row['parent_cat'];
              $status       =   $row['status'];
    ?>

              <!-- Content Header (Page header) -->
              <div class="content-header">
                <div class="container-fluid">
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <h1 class="m-0">Edit category</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="categories.php?do=manage">categories</a></li>
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
                    <form action="categories.php?do=update" method="POST">
                      <div class="form-group">
                        <label for="input-category-name">Category Name</label>
                        <input type="text" name="cat_name" value="<?php echo $cat_title ?>" class="form-control" id="input-category-name" placeholder="Type category name here" required="required">
                      </div>

                      <div class="form-group">
                        <label for="input-category-desc">Category Description</label>
                        <textarea name="cat_desc" rows="3" class="form-control" id="input-category-desc" placeholder="Type Category Description here"><?php echo $cat_desc ?></textarea>
                      </div>


                      <div class="form-group">
                        <label for="input-parent-cat">Parent Category</label>
                        <select class="form-control" name="parent_cat" id="input-parent-cat">
                          <option value="" <?php echo (empty($parent_cat))? "selected": ""; ?>>None</option>
                          <?php
                            if (empty($parent_cat)) {
                              SelectCatSubCat($db, "add");
                            }
                            else{
                              SelectCatSubCat($db, "add", $parent_cat);
                            }
                          ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="input-category-status">Category Status</label>
                        <select class="form-control" name="status" id="input-category-status">
                              <option value="0" <?php if($status == 0){echo "selected";}?>>Inactive</option>
                              <option value="1" <?php if($status == 1){echo "selected";}?>>Active</option>
                        </select>
                      </div>
                    <input type="hidden" value="<?php echo $updateID?>" name="updateID">
                    <button class="btn btn-info mt-3" name="update-category" type="submit">Update Category</button>
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
        if (isset($_POST['update-category'])) {
          $updateID     =   mysqli_real_escape_string($db, $_POST['updateID']);
          $cat_name     =   mysqli_real_escape_string($db, $_POST['cat_name']);
          $cat_desc     =   mysqli_real_escape_string($db, $_POST['cat_desc']);
          $status       =   mysqli_real_escape_string($db, $_POST['status']);
          $parent_cat   =   $_POST['parent_cat'];


          
          
          // Updating Category
          if(!empty($_POST['parent_cat'])){
            $parent_cat =   mysqli_real_escape_string($db, $_POST['parent_cat']);
            $sql        =   "UPDATE categories SET cat_title = '$cat_name', cat_desc = '$cat_desc', parent_cat = '$parent_cat', status = '$status' WHERE cat_id ='$updateID'";
          }
          else{
            $sql        =   "UPDATE categories SET cat_title = '$cat_name', cat_desc = '$cat_desc',parent_cat = null, status = '$status' WHERE cat_id ='$updateID'";
          }
          
          $res          =   mysqli_query($db, $sql);
          if($res){
            header("Location: categories.php?do=manage");
          }
          else{
            die("Error: " . mysqli_error($db));
          }
        }
        else{
          header("Location: categories.php?do=manage");
        }
      }

      else if($do == "delete"){
        if(isset($_GET['delid'])){
          $deleteID = $_GET['delid'];
          $dataArray =  [
            'table'     =>  'categories',
            'id'        =>  'cat_id',
            'parent_by' =>  'parent_cat',
            'redirect'  =>  '?do=manage',
            'table'     =>  'categories',
          ];

          adminDynamicDelete($dataArray, $deleteID, $db);
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