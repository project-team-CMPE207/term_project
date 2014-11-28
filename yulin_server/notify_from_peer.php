<?php 

require_once "file_db.php";
require_once "utility.php";
require_once "/fileserver/file_server_lib.php";

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
  Util::end_with_msg("file's md5 id is missing");
}

// perform task depending on notification type
if ($action == "update") {
  FileDB::init();
  $success = FileDB::update_record($md5_id, $title, $category, $desc);
  if(!$success) {
    Util::end_with_msg("update failed");
  }

  FileDB::close();
  echo "Yulin's Server: Updated successfully!";

} elseif ($action == "delete") {
  FileDB::init();
  
  $success = Util::delete_file_by_id($md5_id) && FileDB::delete_record($md5_id);
  if(!$success) {
    Util::end_with_msg("deletion failed");
  }

  FileDB::close();
  echo "Yulin's Server: Deleted successfully!";

} else {
  Util::end_with_msg("Unknown action: ".$action.". The only accepted notification actions are update and delete.");
}

?>