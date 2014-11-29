<?php 
require_once "file_db.php";

class Util {
  public static function log($msg) {
    $log = fopen("file_server_log.txt","a");
    fwrite($log,date("h:i:sa").": ".$msg);
    fclose($log);
  }

  public static function log_and_echo($msg) {
    self::log($msg);
    echo $msg;
  }

  public static function log_and_die($msg){
    FileDB::close();
    self::log($msg);
    die($msg);
  }

  public static function delete_file_by_id($md5_id) {
    // README: assume a connecion has already been created
    $file_path = FileDB::get_file_path($md5_id);
    unlink($file_path);
  }

}

?>