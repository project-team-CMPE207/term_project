<?php 
// server name
putenv ("SERVER_NAME=yulin");

require_once "file_db.php";
require_once "utility.php";
require_once "/fileserver/file_server_lib.php";

// ======================================================================================================
// Main block begins
// ======================================================================================================

Util::log("Request received: client upload");

// extract picture information
$action   = $_POST['action'];
$from     = $_POST['from'];
$md5_id   = $_POST['md5_id'];
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

if ($action != "update" && $action != "delete") {
  Util::end_with_msg("Bad Request: unknown action: ".$action);
}

// file id check
if(!$md5_id) {
  Util::end_with_msg("Bad Request: file's md5 id is missing");
}

// perform task depending on notification type
FileDB::init();

if ($action == "update") {
  $success = FileDB::update_record($md5_id, $title, $category, $desc);
  if(!$success) {
    Util::end_with_msg("Server error: file info update failed");
  }
  echo "Request processed: file info updated successfully";
  Util::log("Request processed: file info updated successfully");

} elseif ($action == "delete") {
  $success = Util::delete_file_by_id($md5_id) && FileDB::delete_record($md5_id);
  if(!$success) {
    Util::end_with_msg("Server error: file deletion failed");
  }
  echo "Request processed: file deleted successfully";
  Util::log("Request processed: file deleted successfully");
}

FileDB::close();

?>