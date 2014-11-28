<?php

// TODO: wrap db in a class

$dbhost = 'localhost:3306';
$dbuser = 'admin';
$dbpass = 'yahoo#Y33Z40G50';

$conn;

function db_init() {
  $conn = mysql_connect($dbhost, $dbuser, $dbpass);
  if(! $conn )
  {
    die('Could not connect: ' . mysql_error());
  }
  mysql_select_db( 'file_server' );
}

function db_check_duplicate($md5_id) {
  // TODO: implement
  return $is_duplicate;
}

function db_insert_record($file_path, $from, $md5_id, $title, $category, $desc) {
  // TODO: implement
  return $success;
}

function db_update_record($md5_id, $title, $category, $desc) {
  // TODO: implement
  return $success;
}

function db_delete_record($md5_id) {
  // TODO: implement
  return $success;
}

function db_close() {
  if ($conn) {
    mysql_close($conn);
  }
}

?>