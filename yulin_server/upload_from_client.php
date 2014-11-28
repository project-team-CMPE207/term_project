<?php 
// server name
putenv ("SERVER_NAME=yulin");

require_once "file_db.php";
require_once "utility.php";
require_once "/fileserver/file_server_lib.php";

// ======================================================================================================
// Configuration block begins
// ======================================================================================================

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
$title    = $_POST['title'];
$category = $_POST['category'];
$desc     = $_POST['desc'];

// check file data
if(!$file) {
  Util::end_with_msg("file data is empty");
}

// check required field
if(!$file || !$from || !$title) {
  Util::end_with_msg("required fields are missing for upload. Make sure you post these fields: $file, $from, $title");
}

// generate md5 id for uploaded file
$md5_id = md5_file($file["tmp_name"]);
if (!$md5_id) {
  Util::end_with_msg("can't generate md5 id for uploaded file");
}

// initiate db connection
FileDB::init();

// duplication check
if (FileDB::check_duplicate($md5_id)){
  Util::end_with_msg("duplicated file id: ".$md5_id);
};

// type and size check
$type = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
$size = $_FILES['file']['size'];
if ($size>MAXSIZE) {
 Util::end_with_msg("exceed file size limit: 4 MB");
} elseif (!in_array($type, $allowed_types)) {
 Util::end_with_msg("unacceptable file format. The supported formats are: ".implode(", ", $allowed_types));
}

// build upload path
$upload_dir   = "uploads/";
$ext          = $type;
$upload_path  = $upload_dir.$md5_id.".".$ext;

// save the uploaded file to filesystem and add record to database
$success = move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path) && FileDB::insert_record($upload_path, "yulin's client", $md5_id, $title, $category, $desc);
if ($success) {
} else {
  Util::end_with_msg("upload failed");
}
echo "Yulin's Server: file uploaded successfully.";

// send the new file to peer servers
$success = send_to_peers($upload_path, $md5_id, $title, $category, $desc);
if (!$success) {
  echo "Warning: at least one peer didn't get the file";
}
echo "Yulin's Server: send file to all peers successfully!";

FileDB::close();
?>