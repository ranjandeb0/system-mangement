<?php 
  $dataArray = [
        'title'         =>  'cat_title', // Print something of parent category
        'id'            =>  '1', // Name of id column of table
        'status_by'     =>  'status',
        'refer_by'      =>  'category', //What you want to refer every single row of this table by
    ];

  function doEcho($dataArray){
    echo"<div class='modal fade' id='modal-default-{$dataArray['id']}'  aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h4 class='modal-title'>Are you sure?</h4>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
            </div>
            <div class='modal-body'>
          <p>Do you want to delete the {$dataArray['title']} {$dataArray['refer_by']}?";

    if(!empty($dataArray['parent_by']) && mysqli_num_rows($sub_res) > 0)
      {echo "<b> All of it's child {$dataArray['refer_by']} will be deleted too.</b>  ";}

    echo "</p>
            </div>
            <div class='modal-footer justify-content-between'>
              <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
              <a href='categories.php?do=delete&delid={$dataArray['id']}'><button type='button' class='btn btn-primary'>Delete</button></a>
            </div>
          </div>
        </div>
      </div>";
  }

  $testArray = [
    'function1' => 'doEcho'
  ];

  $testArray['function1']($dataArray);
?>