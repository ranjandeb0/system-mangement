<?php
	include "globalVariables.php"; //This consists of all the global variable used in this php file
	

	// Read post from a category and all it's subcategory function 
	function readpost($db, $main_cat_id = null){
		if(!empty($main_cat_id)){
			global $cat_id;
			$cat_id[] = $main_cat_id;


			$sql = "SELECT cat_id FROM categories WHERE parent_cat = '$main_cat_id'";
			$res = mysqli_query($db, $sql);
			if (mysqli_num_rows($res) > 0) {
				while($row = mysqli_fetch_array($res)){
					$secondary_id = $row['cat_id'];
					readpost($db, $secondary_id);
				}
			}
			$sql = "SELECT * FROM posts WHERE category_id IN (" . implode(',', array_map('intval', $cat_id)) .  ") AND status = '1' ORDER BY post_id DESC LIMIT 10";
		}
		else{
			$sql = "SELECT * FROM posts WHERE status = '1' ORDER BY post_id DESC LIMIT 10";
		}
		
		$res = mysqli_query($db, $sql);
		if(!$res){
			die("Error: " . mysqli_error($db));
		}
		else{
			return $res;
		}
	}

	// Function to upload a post to database
	function uploadPost($post_title, $post_content, $post_category, $metatags, $status, $img){
		global $db;
		global $logged_user_id;
		$post_title     =   mysqli_real_escape_string($db, $_POST[$post_title]);
    $post_content   =   mysqli_real_escape_string($db, $_POST[$post_content]);
    $category_id    =   mysqli_real_escape_string($db, $_POST[$post_category]);
    $metatags       =   mysqli_real_escape_string($db, $_POST[$metatags]);
    //uploaded file
    $image      =   $_FILES[$img]['name'];


		if(empty($status)){
			$status = 1;
		}
		else{
			$status = mysqli_real_escape_string($db, $_POST[$status]);
		}

    if(empty($image)){
      $sql = "INSERT INTO posts (post_title, post_content, post_datetime, category_id, author_id, metatags,  status) VALUES ('$post_title', '$post_content', now(), '$category_id', '$logged_user_id', '$metatags', '$status')";
    }
    else{
      $image_tmp  =   $_FILES[$img]['tmp_name'];
      
      $image_name =   "post-" . rand(0,9999999) . "-" . $image;
      
      move_uploaded_file($image_tmp, "assets/img/posts/" . $image_name);

      $sql = "INSERT INTO posts (post_title, post_content, post_datetime, category_id, author_id, image, metatags,  status) VALUES ('$post_title', '$post_content', now(), '$category_id', '$logged_user_id', '$image_name', '$metatags', '$status')";
    }

    $res = mysqli_query($db, $sql);
    if($res){
      header("Location: index");
    }
    else{
      die("Error: " . mysqli_error($db));
    }
	}




	// Dropdown of Category Sub Category function
	function DropDownCatSubCat($db){
		global $loggedIn;
		$sql = "SELECT * FROM categories WHERE parent_cat IS NULL AND status = '1' ORDER BY cat_title ASC";
  	$res = mysqli_query($db, $sql);
      if(!$res){
      	die("Error: " . mysqli_error($db));
      }
      else{
        while ($row = mysqli_fetch_assoc($res)) {
        $hierarchy = 0;
        $cat_id       =   $row['cat_id'];
        $cat_title    =   $row['cat_title'];
		?>
      <li <?php echo ($loggedIn)? "" : "class = 'nav-item'" ; ?>>
        <!-- Split dropend button -->
				<div class="btn-group <?php echo ($loggedIn)? "dropend" : "" ; ?>">
					<a <?php echo ($loggedIn)? "" : "class = 'nav-link'" ; ?> href="?cat=<?php echo $cat_id;?>"><?php echo $cat_title;?></a>
					<?php DropDownSubCat($cat_id, $db, $hierarchy); ?>
				</div>
			</li>
        <?php
            }
          }
	}

	// Sub Category Dropdown Recursive function
	function DropDownSubCat($cat_id, $db, $hierarchy){
		global $loggedIn;
		$hierarchy += 1;

		if($hierarchy < $GLOBALS['SubCatLimit']){
			$sql = "SELECT * FROM categories WHERE parent_cat = '$cat_id' AND status = '1' ORDER BY cat_title ASC";
	  	$sub_res = mysqli_query($db, $sql);
	  	if(!$sub_res){
	  		die("Error: " . mysqli_error($db));
	  	}

			if (mysqli_num_rows($sub_res) > 0) {
		?>
				<button type="button" class="btn <?php echo ($loggedIn)? "btn-secondary" : "" ; ?> dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
					<span class="visually-hidden">Toggle Dropright</span>
				</button>
				<ul class="dropdown-menu">
				<?php
					while($row = mysqli_fetch_assoc($sub_res)){
						$cat_id       =   $row['cat_id'];
						$cat_title    =   $row['cat_title'];
				?>
				<!-- Dropdown menu links -->
					<li <?php echo ($loggedIn)? "" : "class = 'nav-item'" ; ?>>
						<!-- Split dropend button -->
						<div class="btn-group dropend">
							<a <?php echo ($loggedIn)? "" : "class = 'nav-link'" ; ?> href="?cat=<?php echo $cat_id;?>"><?php echo $cat_title;?></a>
							<?php DropDownSubCat($cat_id, $db, $hierarchy); ?>
						</div>
					</li>
				<?php
					}
				?>
				</ul>
		<?php
			}
		}
	}




	// Form Category Select Options listing function
	function SelectCatSubCat($db, $do = "add", $parent_cat_id = null){
		// Do can be either add or post
		if(!$do == "post"){
			$do = "add";
		}
		$separator = "---";
		$sql = "SELECT * FROM categories WHERE parent_cat IS NULL AND status = '1' ORDER BY cat_title ASC";
    $res = mysqli_query($db, $sql);
    if(!$res){
    	die("Error: " . mysqli_error($db));
    }
    else{
      while ($row = mysqli_fetch_assoc($res)) {
      	$hierarchy = 0;
        $cat_id       =   $row['cat_id'];
        $cat_title    =   $row['cat_title'];
		?>
				<option value="<?php echo $cat_id;?>" <?php echo (strcasecmp($cat_title, "uncategorized") == 0 && $do == "post")? "selected" : "" ;?> <?php if($do == "add" && !empty($parent_cat_id) && $parent_cat_id == $cat_id){echo "selected"; $parent_cat_id = null;} ?>></option>
		<?php
				
				if (empty($parent_cat_id)) {
          SelectOptionsSubCat($cat_id, $db, $hierarchy, $separator, $do);
        }
        else{
          SelectOptionsSubCat($cat_id, $db, $hierarchy, $separator, $do, $parent_cat_id);
        }
      }
    }
	}

	// Category select options recursive function
	function SelectOptionsSubCat($main_cat_id, $db, $hierarchy, $separator, $do, $parent_cat_id = null){
		$hierarchy += 1;
		if($do == "add"){
			$repeat = $hierarchy < $GLOBALS['SubCatLimit'] - 1;
		}
		elseif ($do == "post") {
			$repeat = $hierarchy < $GLOBALS['SubCatLimit'];
		}
		else{
			die("Cat Sub cat Select Option Function error: do variable");
		}
		if($repeat){
			$sql = "SELECT * FROM categories WHERE parent_cat = '$main_cat_id' AND status = '1' ORDER BY cat_title ASC";
	    $sub_res = mysqli_query($db, $sql);
	    if(!$sub_res){
	    	die("Error: " . mysqli_error($db));
	    }
	    else{
	    	if(mysqli_num_rows($sub_res)>0){
	    		while ($row = mysqli_fetch_assoc($sub_res)) {
		        $cat_id       =   $row['cat_id'];
		        $cat_title    =   $row['cat_title'];
			?>
						<option value="<?php echo $cat_id;?>" <?php if($do == "add" && !empty($parent_cat_id) && $parent_cat_id == $cat_id){echo "selected"; $parent_cat_id = null;} ?> ><?php echo str_repeat($separator, $hierarchy) . $cat_title;?></option>
			<?php
						if (empty($parent_cat_id)) {
		          SelectOptionsSubCat($cat_id, $db, $hierarchy, $separator, $do);
		        }
		        else{
		          SelectOptionsSubCat($cat_id, $db, $hierarchy, $separator, $do, $parent_cat_id);
		        }
		      }
	    	}
	    }
		}
	}


	
	function adminDynamicListTable(array $getDataArray, $db, $parent_cat_id = null, $parent_sl = null){
		$dataArray = [
				'table' 				=>	'',	// Table name to print from
				'parent_by' 		=>	'',	// If there is parental system then the 'column' name of it
				'recurse' 			=>	true,	// Assign false if you want non recursive parental rows
				'order_by' 			=>	'',	//	Order the rows by 'column'
				'order_type' 		=>	'ASC',	//	Order type - ASC or DESC
				'title' 				=>	'', // Print something of parent category
				'id'						=>	'',	// Name of id column of table
				'status_by' 		=> 	'',
				'refer_by'			=>	'', //What you want to refer every single row of this table by
				'table_class' 	=> 	'table m-0 table-bordered admin-list',
				'arr_action_do'	=> '',
				'arr_serial_do'	=> '',
				'print_columns' => [
					'arr_serial' 		=> 'Sl.',	//Use $sl as counter
					'cat_title' 		=> 'Category title',	//If you want any column to have custom task have column_do in parent array
					'cat_desc'			=> 'Description cat',	//Assign executable php code using column name variable as a string as it's value
					'status'				=> 'Status',
					'arr_action'		=>	'Action'
				]
			];
		global $logged_user_role;
		function endsWith($string, $endString)
		{
	    $len = strlen($endString);
	    if ($len == 0) {
        return true;
	    }
	    return (substr($string, -$len) === $endString);
		}

		foreach(array_keys($getDataArray) as $column){
			if(!endsWith($column, "do")){
				$dataArray[$column] = $getDataArray[$column];
			}
		}


		$dataArray['arr_action_list'] = "echo \" <ul class='actions'>
	      <li>
	        <a  href = '?do=edit&uid=\${$dataArray['id']}';> <i class='fas fa-edit'></i> </a>
	      </li>
	      <li>
	        <a href='#' data-toggle='modal' data-target='#modal-default-\${$dataArray['id']}'> <i class='fas fa-trash'></i> </a>
	      </li>
			</ul>";

		$dataArray['arr_action_del_modal'] = "<div class='modal fade' id='modal-default-\${$dataArray['id']}' style='display: none;' aria-hidden='true'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<h4 class='modal-title'>Are you sure?</h4>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
								<span aria-hidden='true'>×</span>
							</button>
						</div>
						<div class='modal-body'>
							<p>Do you want to delete the\" . (empty(\$dataArray['title'])? '' : \" \${\$dataArray['title']}\") . \" {\$dataArray['refer_by']}?\";
							if(!empty(\$dataArray['parent_by']) && mysqli_num_rows(\$sub_res) > 0){echo \"<b> All of it's child {\$dataArray['refer_by']} will be deleted too.</b>  \";}
							echo \"</p>
						</div>
						<div class='modal-footer justify-content-between'>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
							<a href='?do=delete&delid=\${$dataArray['id']}'><button type='button' class='btn btn-primary'>Delete</button></a>
						</div>
					</div>
				</div>
			</div>
		\"; ";

		$dataArray['arr_action_do'] = $dataArray['arr_action_list'] . $dataArray['arr_action_del_modal'];

		$dataArray['arr_serial_do'] =  'echo $sl;';

		$parental_action = $dataArray['parent_by'] .  '_do';
		$dataArray[$parental_action] = "if(!empty(\${\$column})){
	      \$sql = \"SELECT * FROM {\$dataArray['table']} WHERE {\$dataArray['id']} = '\${\$dataArray['parent_by']}'\";
	      \$sub_res2 = mysqli_query(\$db, \$sql);
	      if(!\$sub_res2){
	        die('Error: ' . mysqli_error(\$db));
	      }
	      if(!empty(mysqli_num_rows(\$sub_res2) != 0)){
	        \$row1 = mysqli_fetch_assoc(\$sub_res2);
	        echo \"<span class='badge badge-info'>\" . \$row1[\$dataArray['title']] . \"</span>\";
	      }else{
	        echo \"<span class='badge badge-danger'>Invalid</span>\";
	      }
	    }else{
	      echo \"<span class='badge badge-light'>isParent</span>\";
	    }";

    $status_action = $dataArray['status_by'] .  '_do';
    $dataArray[$status_action]	=	"if(\${\$column} == 0)
            { echo \"<span class='badge badge-danger'>inactive</span>\"; }
          else if(\${\$column} == 1)
            { echo \"<span class='badge badge-success'>active</span>\";}";

    foreach(array_keys($getDataArray) as $column){
    	if(endsWith($column, "do")){
				$dataArray[$column] = $getDataArray[$column];
			}
		}
		

		if(empty($parent_cat_id)){
			$sql = "SELECT * FROM {$dataArray['table']}" . (!empty($dataArray['parent_by']) ? " WHERE {$dataArray['parent_by']} IS NULL": "") . (!empty($dataArray['order_by'])? " ORDER BY {$dataArray['order_by']} {$dataArray['order_type']}" : "");
		}
		else{
			$sql = "SELECT * FROM {$dataArray['table']} WHERE {$dataArray['parent_by']} = '$parent_cat_id'" . (!empty($dataArray['order_by'])? " ORDER BY {$dataArray['order_by']} {$dataArray['order_type']}": "");
		}


    $res = mysqli_query($db, $sql);
    if(!$res){
    	die("MySqli Error: " . mysqli_error($db));
    }

    //printing table
    echo "<table" . (!empty($dataArray['table_class'])? " class = '{$dataArray['table_class']}'": "") . ">";
    echo "<thead><tr>";
    foreach ($dataArray['print_columns'] as $column) {
    	echo "<th> {$column} </th>";	//printing heading of the table
    }
    echo "</tr></thead><tbody>";


    function recurseList(array $dataArray, $res, $db, $parent_sl = null){
    	$child_sl = 0;
	    while ($row = mysqli_fetch_assoc($res)) {
	    	$child_sl++;
	      if(!empty($parent_sl)){
	      	$sl = $parent_sl . "." . $child_sl;
	      }
	      else{
	      	$sl = $child_sl;
	      }

	      ${$dataArray['id']}	=  $row["{$dataArray['id']}"];

	      
	      foreach (array_keys($dataArray['print_columns']) as $column) {
	      	if(!str_starts_with($column, 'arr')){
	      		${$column}    =   $row[$column];
	      	}
	      }
	      if(!empty($dataArray['parent_by'])){
	      	$sql = "SELECT * FROM {$dataArray['table']} WHERE {$dataArray['parent_by']} = '${$dataArray['id']}' " . (!empty($dataArray['order_by'])? " ORDER BY {$dataArray['order_by']} {$dataArray['order_type']}": "");
			    $sub_res = mysqli_query($db, $sql);
			  }

	      echo "<tr>";
	      foreach (array_keys($dataArray['print_columns']) as $column) {
		      	echo "<td>";
		      	$key_check = $column . "_do";
		      	if(array_key_exists($key_check, $dataArray)){
		      		eval("$dataArray[$key_check]");
		      	}
		      	else{
		      		echo ${$column};
		      	}
		      echo "</td>";
		    }
		    echo "</tr>";

		    if($dataArray['recurse'] && !empty($dataArray['parent_by']) && mysqli_num_rows($sub_res) > 0){
		    	recurseList($dataArray, $sub_res, $db, $sl);
		    }
		  }
	  }
	  if(mysqli_num_rows($res) > 0){
	  	recurseList($dataArray, $res, $db);
	  }
	  else{
	  	echo "<tr><td colspan='100%'>No {$dataArray['refer_by']} to Show</td></tr>";
	  }
	  echo "</tbody></table>";
	}

	function adminDynamicDelete(array $getDataArray, $delId, $db){
		$dataArray =	[
			'table'			=>	'categories',
			'id'				=>	'cat_id',
			'parent_by'	=>	'parent_cat',
			'redirect'	=>	'?do=manage',
			'table'			=>	'categories',
		];

		foreach (array_keys($getDataArray) as $column) {
			$dataArray[$column] = $getDataArray[$column];
		}

		if(!empty($dataArray['parent_by'])){
			global $ids;
			function recurseArray($dataArray, $delId, $db){
				global $ids;
				$ids[] = $delId;

				$sql = "SELECT {$dataArray['id']} FROM {$dataArray['table']} WHERE {$dataArray['parent_by']} = '$delId'";
				$res = mysqli_query($db, $sql);
				if(!$res){
					die("MySqli Error: " . mysqli_error($db));
				}
				if (mysqli_num_rows($res) > 0) {
					while($row = mysqli_fetch_array($res)){
						$secondary_id = $row['cat_id'];
						recurseArray($dataArray, $secondary_id, $db);
					}
				}
			}
			recurseArray($dataArray, $delId, $db);
			$sql1 = "DELETE FROM {$dataArray['table']} WHERE {$dataArray['id']} IN (" . implode(',', array_map('intval', $ids)) .  ") ";
		}
		else{
			$sql1 = "DELETE FROM {$dataArray['table']} WHERE {$dataArray['id']} = '$delId'";
		}

		if($dataArray['table'] == 'categories'){
			$sql2 = "SELECT post_id FROM posts WHERE category_id IN (" . implode(',', array_map('intval', $ids)) .  ")";
			$res = mysqli_query($db, $sql2);
			if(!$res){
				die("MySqli Error: " . mysqli_error($db));
			}
			else{
				if(mysqli_num_rows($res) > 0){
					while($row = mysqli_fetch_assoc($res)){
						$postIds[] = $row['post_id'];
					}
					$sql3 = "UPDATE posts SET category_id = '0' WHERE post_id IN (" . implode(',', array_map('intval', $postIds)) .  ")";
				}
			}
		}
		$sql = $sql1 . (isset($sql3)? ";" . $sql3 : "");

		$res = mysqli_multi_query($db, $sql);
    if($res){
      header("Location: {$dataArray['redirect']}");
    }
    else{
      die("Error: " . mysqli_error($db));
    }
	}

	function readAndPrintComments($db, $comment_res, bool $reply = false, int $limit = 0, int $singleComment = 0, int $fromBefore = 0, int $fromAfter = 0){
		global $logged_user_id;

		if(! $comment_res instanceof mysqli_result){
			if($reply){
				$sql = "SELECT * FROM comments WHERE reply_of = '$comment_res' and status = '1' ORDER BY id asc". (($limit > 0)? " LIMIT {$limit}": "");
			}else{
				if($fromBefore > 0){
					$sql  = "SELECT * FROM comments WHERE reply_of is null and post_id='$comment_res'". (($singleComment > 0) ? " AND id = '{$singleComment}' OR id < '{$fromBefore}'": " AND id < '{$fromBefore}'") . " and status = '1' ORDER BY id desc". (($limit > 0) ? " LIMIT {$limit}": "");
				}
				else{
					if($fromAfter > 0){
						$sql  = "SELECT * FROM comments WHERE reply_of is null and post_id='$comment_res'". (($singleComment > 0) ? " AND id = '{$singleComment}' OR id > '{$fromAfter}'": "AND id > '{$fromAfter}'") . " and status = '1' ORDER BY id desc". (($limit > 0) ? " LIMIT {$limit}": "");
					}
					else{
						$sql  = "SELECT * FROM comments WHERE reply_of is null and post_id='$comment_res'". (($singleComment > 0) ? " and id = '{$singleComment}'": "") . " and status = '1' ORDER BY id desc". (($limit > 0) ? " LIMIT {$limit}": "");
					}
				}					
			}
			$comment_res = mysqli_query($db, $sql);
			if (!$comment_res) {
				die("Error: " . mysqli_error($db));
			}
		}

		while ($row   = mysqli_fetch_assoc($comment_res)) {
				$id 			= 	$row['id'];
				$author_id 		= 	$row['author_id'];
				$comment 		= 	$row['comment'];
				date_default_timezone_set('Asia/Dhaka');
				$date_time_diff = 	date_diff(new DateTime($row['date_time']), new DateTime());
				$sql 			= 	"SELECT fullname,image FROM users WHERE id = '$author_id'";
				$sub_res 		= 	mysqli_query($db, $sql);
				$row 			= 	mysqli_fetch_assoc($sub_res);
				$author_name	=	$row['fullname'];
				$author_image	=	$row['image'];

				$sql  = "SELECT * FROM comment_reactions WHERE comment_id='$id' AND user_id='$logged_user_id'";
				$react_res	= mysqli_query($db, $sql);
				if(!$react_res){
					die("MySqli Error: " . mysqli_error($db));
				}
				else{
					$reacted	=	mysqli_num_rows($react_res);
				}

				$sql  = "SELECT * FROM comment_reactions WHERE comment_id='$id'";
				$react_res	= mysqli_query($db, $sql);
				if(!$react_res){
					die("MySqli Error: " . mysqli_error($db));
				}
				else{
					$reactCount	=	mysqli_num_rows($react_res);
				}

				if($date_time_diff->y != 0){
					$date_time 		= 	$date_time_diff->format('%y year' . (($date_time_diff->y > 1)? 's' : '') . ' ago');
				}
				else if($date_time_diff->m != 0){
					$date_time 		= 	$date_time_diff->format('%m month' . (($date_time_diff->m > 1)? 's' : '') . ' ago');
				}
				else if($date_time_diff->d != 0){
					$date_time 		= 	$date_time_diff->format('%d day' . (($date_time_diff->d > 1)? 's' : '') . ' ago');
				}
				else if($date_time_diff->h != 0){
					$date_time 		= 	$date_time_diff->format('%h hour' . (($date_time_diff->h > 1)? 's' : '') . ' ago');
				}
				else{
					$date_time 		= 	$date_time_diff->format('%i minute' . (($date_time_diff->i > 1)? 's' : '') . ' ago');
				}
			?>
				<div class="comment py-2" data="<?php echo $id; ?>">
					<div class="comment-info">
						<div class="author-image" data="<?php echo $author_id ?>"><img src="admin/dist/img/users/<?php echo (empty($author_image))?  'default-img.png' :  $author_image ?>"  alt="Author Image"></div>
						<div class="author" data="<?php echo $author_id ?>"><?php echo $author_name; ?></div>
						<div class="time-delta"><?php echo $date_time ?></div>
						<div class="comment-reactions ms-auto text-info">
							<span class="react-count"><?php echo $reactCount ?></span>
							reactions
						</div>
						<?php if($logged_user_id === $author_id) {?>
							<div class="user-control">
								<i class="fas fa-ellipsis-h" id="dropdownMenuLink" data-bs-toggle="dropdown"></i>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
							    <li class="dropdown-item edit-comment">Edit</li>
							    <li class="dropdown-item delete-comment">Delete</li>
							  </ul>
							</div>
						<?php } ?>
					</div>
					<div class="comment-text"><?php echo $comment; ?></div>
					<div class="comment-action">
						<ul class="action-list">
							<li>
								<span class="action react <?php echo ($reacted)? "reacted" : "" ?>"><?php echo ($reacted)? "Liked" : "React" ?></span>
							</li>
							<li>
								<span class="action reply show">Reply</span>
							</li>
						</ul>
					</div>
					<?php 
						$sql = "SELECT * FROM comments WHERE reply_of = '$id' and status = '1' ORDER BY id asc";
						$sub_res = mysqli_query($db, $sql);
						if (!$sub_res) {
							die("Error: " . mysqli_error($db));
						}
						if(mysqli_num_rows($sub_res) > 0){
							echo "<span class='view-replies link-colored'>See replies</span>";
						}
					?>
				</div>
			<?php
		}
	}

	function generateCommentForm(){
		global $logged_user_image;
		?>
			<form style="display:none" class="comment-form mt-3">
 				<div class="user-image ms-auto">
					<img src="admin/dist/img/users/<?php echo (empty($logged_user_image))?  'default-img.png' :  $logged_user_image ?>"  alt="User Image">
				</div>
	 			<div class="input-group">
	 				<textarea rows="2" style="resize: none;" class="form-control bg-transparent text-white" name="comment-text" placeholder="type your comment here.." autocomplete="off" ></textarea> 
	 				<input class="btn bg-gradient border-white text-white" type="submit" name="submit-comment"  />
	 			</div>
	 		</form>
		<?php
	}

?>