<?php 

require("db.php");
require("/fileserver/file_server_lib.php");

// ======================================================================================================
// Configuration block begins
// ======================================================================================================
// server name
putenv ("SERVER_NAME=yulin");

// ======================================================================================================
// Main block begins
// ======================================================================================================

// extract picture information
$action   = $_POST['action'];
$from     = $_POST['from'];
$md5_id   = $_POST['md5_id'];
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

// file id check
if(!$md5_id) {
  end_with_msg("file's md5 id is missing");
}

// perform task depending on notification type
if ($action == "update") {
  db_init();
  $success = db_update_record($md5_id, $title, $category, $desc);
  if(!$success) {
    end_with_msg("update failed");
  }

  db_close();
  echo "Yulin's Server: Updated successfully!";

} elseif ($action == "delete") {
    db_init();
    // TODO: add file deletion logic
    $success = db_delete_record($md5_id);
    if(!$success) {
      end_with_msg("deletion failed");
    }

    db_close();
    echo "Yulin's Server: Deleted successfully!";

} else {
  end_with_msg("Unknown action: ".$action.". The only accepted notification actions are update and delete.");
}


// ======================================================================================================
// Helper functions definition block begins
// ======================================================================================================
function end_with_msg($msg){
  echo "Yulin's Server Error: ".$msg;
  db_close();
  die;
} 
?>