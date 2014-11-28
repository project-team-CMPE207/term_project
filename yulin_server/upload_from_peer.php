<?php 

require("db.php");
require("/fileserver/file_server_lib.php");

// ======================================================================================================
// Configuration block begins
// ======================================================================================================
// server name
putenv ("SERVER_NAME=yulin");

// limit upload file to image types
$allowed_types  = array(
  "jpg",
  "jpeg",
  "bmp",
  "gif",
  "png",
  "tiff"
  );

// maxmum upload size
define("MAXSIZE", 4096);

// ======================================================================================================
// Main block begins
// ======================================================================================================

// extract picture information
$file     = $_FILES['file'];
$from     = $_POST['from'];
$md5_id   = $_POST['md5_id'];
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

// check file data
if(!$file) {
  end_with_msg("file data is empty");
}

// check required field
if(!$file || !$from || !$md5_id || !$title) {
  end_with_msg("required fields are missing for upload. Make sure you post these fields:$from, $md5_id, $title");
}

// initiate db connection
db_init();

// duplication check
if (db_check_duplicate($md5_id)){
  end_with_msg("duplicated file id: ".$md5_id);
};

// type and size check
$type = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
$size = $_FILES['file']['size'];
if ($size>MAXSIZE) {
 end_with_msg("exceed file size limit: 4 MB");
} elseif (!in_array($type, $allowed_types)) {
 end_with_msg("unacceptable file format. The supported formats are: ".implode(", ", $allowed_types));
}


// build upload path
$upload_dir   = "uploads/";
$ext          = $type;
$upload_path  = $upload_dir.$md5_id.".".$ext;

// save the uploaded file to filesystem and add record to database
$success = move_uploaded_file($file["tmp_name"], $upload_path) && db_insert_record($upload_path, $from, $md5_id, $title, $category, $desc);
if ($success) {
} else {
  end_with_msg("upload failed");
}
db_close();
echo "Yulin's Server: Uploaded successfully!";

// ======================================================================================================
// Helper functions definition block begins
// ======================================================================================================
function end_with_msg($msg){
  echo "Yulin's Server Error: ".$msg;
  db_close();
  die;
} 
?>