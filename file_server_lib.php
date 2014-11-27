<?php
/**
 * This library will be used across all php file servers to implement server-server communication
 * Please read comments in the common configuration block and keep these convention(like file receive page name)
 */

// ======================================================================================================
// Common configuration block begins
// ======================================================================================================


// Read 'SERVER_NAME' from server variable so when sending files it won't send to itself
// To set 'SERVER_NAME' on your server, in all pages that require this library, add this line in the beginning:
// putenv ("SERVER_NAME=$your server name$")
// where $your server name$ is 'annie'|'daniel'|'ken'|'yulin' depend on who you are
$server_name = $_SERVER['SERVER_NAME'];

// By convention, everyone HAS to use this path for script that receive files from peer servers 
$recv_script_name = 'upload_from_peer.php';

// By convention, everyone HAS to use this path for script that get notifications(like file update and delete) from peer servers 
$notify_script_name = 'notify_from_peer.php';

// TODO: edit your entry to reflect the real path to the receive script
$peer_list = array(
  'annie' =>  'http://www.annie-suantak.com/',
  'daniel'=>  'http://www.danielishere.com/', 
  'ken'   =>  'http://www.skctech.net/', 
  'yulin' =>  'http://www.yulinye.com/fileserver/'
  );

// ======================================================================================================
// Protocol methods definition block begins
// ======================================================================================================

/**
 * Send a picture file and its infomation to all peer servers so everyone is keep in sync
 * Should be called after your server received upload from your client
 * Implementation of the receiving script is required; everyone can handle file & DB operations differently 
 *  
 * @param  string $file_path The path of the file; it is recommended to use [md5_id].[ext] to store files
 * @param  string $md5_id    The md5 of the file, used to distinguish files
 * @param  string $title     Title of the picture, which is different from file name
 * @param  string $category  A string that describes the category of the picture
 * @param  string $desc      The description of the picutre
 * @return boolean           A boolean indicate if the upload to all peer servers is successful(true for success)
 */
function send_to_peers($file_path, $md5_id, $title, $category, $desc){
  $success = true;
  foreach($peer_list as $peer_name => $peer_path) {
    if ($peer_name != $server_name) { // avoid send to self
      // get full path of a file on server
      $file_name_with_full_path = realpath($file_path);
      // construct the array for curl post action
      $post_arr = array('file'=>'@'.$file_name_with_full_path, 'from'=>$server_name, 'md5_id'=>$md5_id, 'title'=>$title, 'category'=> $category, 'desc'=>$desc);
      // curl operation
      $result = curl_post($peer_path.$recv_script_name, $post_arr);
      // update status
      $success = $success && $result;
    }
  }

  return $success;
}

/**
 * Notify peer server that the infomation of a file has been changed to keep all peers in sync
 * Should be called after infomation of the file(other than the file itself) is modified
 * Implementation of the receiving script is required; everyone can handle file & DB operations differently
 * 
 * @param  string $md5_id    The md5 of the file, used to distinguish files
 * @param  string $title     Title of the picture, which is different from file name
 * @param  string $category  A string that describes the category of the picture
 * @param  string $desc      The description of the picutre
 * @return boolean           A boolean indicate if the notification to all peer servers is successful(true for success)
 */
function notify_update($md5_id, $title, $category, $desc) {
  $success = true;
  foreach($peer_list as $peer_name => $peer_path) {
    if ($peer_name != $server_name) { // avoid send to self
      // construct the array for curl post action
      $post_arr = array('action'=>'update', 'from'=>$server_name, 'md5_id'=>$md5_id, 'title'=>$title, 'category'=> $category, 'desc'=>$desc);
      // curl operation
      $result = curl_post($peer_path.$notify_script_name, $post_arr);
      // update status
      $success = $success && $result;
    }
  }

  return $success;
}

/**
 * Notify peer server that the a file has been deleted to keep all peers in sync
 * Should be called after a file is deleted
 * Implementation of the receiving script is required; everyone can handle file & DB operations differently
 * @param  string $md5_id    The md5 of the file, used to distinguish files
 * @return boolean           A boolean indicate if the notification to all peer servers is successful(true for success)
 */
function notify_delete($md5_id) {
  $success = true;
  foreach($peer_list as $peer_name => $peer_path) {
    if ($peer_name != $server_name) { // avoid send to self
      // construct the array for curl post action
      $post_arr = array('action'=>'delete', 'from'=>$server_name, 'md5_id'=>$md5_id);
      // curl operation
      $result = curl_post($peer_path.$notify_script_name, $post_arr);
      // update status
      $success = $success && $result;
    }
  }

  return $success;
}


// ======================================================================================================
// Helper functions definition block begins
// ======================================================================================================

/**
 * The common method to send a curl post request
 * @param  string   $url      Recipient url
 * @param  array    $post_arr An array used in curl post
 * @return boolean            A boolean indicate if the post action is successful(true for success)
 */
function curl_post($url, $post_arr){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);
  $result=curl_exec ($ch);
  curl_close ($ch);

  return $result;
}

?>
