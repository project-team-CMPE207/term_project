<?php

require_once "utility.php";
require_once "confidentials.php";

class FileDB {
  private static $conn;

  public static function init() {
    self::$conn = mysql_connect(self::$dbhost, self::$dbuser, self::$dbpass);
    if(!self::$conn)
    {
      die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname);
  }

  public static function check_duplicate($md5_id) {
    // TODO: implement
    return $is_duplicate;
  }

  public static function insert_record($file_path, $from, $md5_id, $title, $category, $desc) {
    // TODO: implement
    return $success;
  }

  public static function update_record($md5_id, $title, $category, $desc) {
    // TODO: implement
    return $success;
  }

  public static function delete_record($md5_id) {
    // TODO: implement
    return $success;
  }

  public static function close() {
    mysql_close(self::$conn);
  }
  
}


?>