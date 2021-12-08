<?php 	include "inc/db.php" ?>
<?php 	//include "inc/functions.php" ?>
<?php 	include "inc/globalVariables.php" ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Test Free Social Platform</title>

	<!-- Bootstrap CSS CDN link -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

	<!-- Custom CSS link -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<?php
	$dataArray = [
				'table' 				=>	'categories',	// Table name to print from
				'parent_by' 		=>	'parent_cat',	// If there is parental system then the 'column' name of it
				'order_by' 			=>	'cat_title',	//	Order the rows by 'column'
				'title' 		=>	'cat_title', // Print something of parent category
				'id'						=>	'cat_id',	// Name of id column of table
				'status_by' 		=> 'status',
				'arr_action_do'	=> '',
				'arr_serial_do'	=> '',
				'parent_cat_do'	=> '',
				'status_do'			=> '',
				'print_columns' => [
					'arr_serial' 		=> 'Sl.',
					'cat_title' 		=> 'Category title',
					'cat_desc'			=> 'Description cat',
					'parent_cat'		=> 'Parent Category',
					'status'				=> 'Status',
					'arr_action'		=>	'Action'
				]
			];
		// }
		$arr_action_do = "echo \" <ul class='actions'>
      <li>
        <a  href = 'categories.php?do=edit&uid=\${$dataArray['id']}';> <i class='fas fa-edit'></i> </a>
      </li>
      <li>
        <a href='#' data-toggle='modal' data-target='#modal-default-\${$dataArray['id']}'> <i class='fas fa-trash'></i> </a>
      </li>
		</ul>

		<div class='modal fade' id='modal-default-<?php echo \${$dataArray['id']}; ?>' style='display: none;' aria-hidden='true'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h4 class='modal-title'>Are you sure?</h4>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
							<span aria-hidden='true'>×</span>
						</button>
					</div>
					<div class='modal-body'>
						<p>Do you want to delete this? <b> If it has any child those will also be deleted.</b></p>
					</div>
					<div class='modal-footer justify-content-between'>
						<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
						<a href='categories.php?do=delete&delid=<?php echo \${$dataArray['id']}; ?>'><button type='button' class='btn btn-primary'>Delete</button></a>
					</div>
				</div>
			</div>
		</div>
		 \"; ";

		$arr_serial_do =  'echo $sl;';

		$parent_cat_do = "if(!empty(\${\$column})){
            \$sql = \"SELECT * FROM {\$dataArray['table']} WHERE {\$dataArray['id']} = '\$dataArray[parent_by]'\";
            \$sub_res = mysqli_query(\$db, \$sql);
            if(!\$sub_res){
              die('Error: ' . mysqli_error(\$db));
            }
            if(mysqli_num_rows(\$sub_res) != 0){
              \$row1 = mysqli_fetch_assoc(\$sub_res);
              echo \"<span class='badge badge-info'>\" . \$row1[\$dataArray['title']] . \"</span>\";
            }else{
              echo \"<span class='badge badge-danger'>Invalid</span>\";
            }
          }else{
            echo \"<span class='badge badge-light'>isParent</span>\";
          }";

     $status_do	=	"if(\${\$column} == 0)
            { echo \"<span class='badge badge-danger'>inactive</span>\"; }
          else if(\${\$column} == 1)
            { echo \"<span class='badge badge-success'>active</span>\";}";
		
		
		$sl = 0;
		if(empty($parent_cat_id)){
			$sql = "SELECT * FROM {$dataArray['table']} WHERE {$dataArray['parent_by']} IS NULL ORDER BY {$dataArray['order_by']} ASC";
		}
		else{
			$sql = "SELECT * FROM {$dataArray['table']} WHERE {$dataArray['parent_by']} = '$parent_cat_id' ORDER BY {$dataArray['order_by']} ASC";
		}

    $res = mysqli_query($db, $sql);
    if(!$res){
    	die("MySqli Error: " . mysqli_error($db));
    }

    echo "<table class='table m-0 table-bordered'>";
    echo "<thead><tr>";
    foreach ($dataArray['print_columns'] as $column) {
    	echo "<th> {$column} </th>";
    }
    echo "</tr></thead><tbody>";
    while ($row = mysqli_fetch_assoc($res)) {
      $sl++;
      if(!empty($parent_sl)){
      	$sl = $parent_sl . "." . $sl;
      }

      ${$dataArray['id']}	=  $row["{$dataArray['id']}"];

      
      foreach (array_keys($dataArray['print_columns']) as $column) {
      	if(!str_starts_with($column, 'arr')){
      		${$column}    =   $row[$column];
      	}
      }

      echo "<tr>";
      foreach (array_keys($dataArray['print_columns']) as $column) {
	      	echo "<td>";
	      	$key_check = $column . "_do";
	      	if(array_key_exists($key_check, $dataArray)){
	      		eval("${$key_check}");
	      	}
	      	else{
	      		echo ${$column};
	      	}
	      	echo "</td>";
	    }
	    echo "</tr>";
	   }
	   echo "</tbody></table>";
?>
</body>
<!-- jQuery -->
<!-- <script src="plugins/jquery/jquery.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>



<!-- <script type="text/javascript">
	$(document).ready(function(){
	
	  $(' .dropdown-toggle').on("click", function(e){
      if($(this).hasClass('show')){
        $(this).next('.dropdown-menu').show();
      }
      e.stopPropagation();
      e.preventDefault();
    });
	});
</script> -->
</html>
