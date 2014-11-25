<?php

# Read my name from server variable
# In the page which require this library, add this line:
# putenv ("MY_NAME=$your name$")
# where your name is 'annie'|'daniel'|'ken'|'yulin' depend on who you are

$my_name = $_SERVER['MY_NAME'];

# Unified path
$recv_path = 'upload_from_peer.php';

# TODO: replace annie's domain name
$peer_list = array(
  'annie' =>  'http://www.annie.com/fileserver/'.$recv_path,
  'daniel'=>  'http://www.danielishere.com/fileserver/'.$recv_path, 
  'ken'   =>  'http://www.skctech.net/fileserver/'.$recv_path, 
  'yulin' =>  'http://www.yulinye.com/fileserver/'.$recv_path
  );

/**
 * 
 * @param  [type] $file_path [description]
 * @param  [type] $uploader  [description]
 * @param  [type] $category  [description]
 * @param  [type] $desc      [description]
 * @return [type]            [description]
 */
function send_to_peers($file_path, $uploader, $category, $desc){
  foreach($peer_list as $name => $url) {
    if ($name != $my_name) {

      $target_url = $url;
      $file_name_with_full_path = realpath($file_path);
      $post = array('file'=>'@'.$file_name_with_full_path, 'from'=>$uploader, 'category'=> $category, 'description'=>$desc);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$target_url);
      curl_setopt($ch, CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      $result=curl_exec ($ch);
      curl_close ($ch);
    }
  }
}

# TODO: implement
function recv_from_peer() {}

# Optional
function notify_delete() {}

# Optional
function notify_update() {}

?>
