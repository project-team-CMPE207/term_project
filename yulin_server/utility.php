<?php 
require_once "file_db.php";

class Util {
  public static function end_with_msg($msg){
    echo "Yulin's Server Error: ".$msg;
    FileDB::close();
    die;
  }

  // TODO: implement
  public static function delete_file_by_id($md5_id) {

  }  
}

?>