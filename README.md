CMPE207 project - by Network Ninjas 
============
## Project scope
* web/mobile applications for picture sharing
* 4 client-server pairs
* 4 servers communicate with each other to sync pictures (the picuture files themselves and the picutre info)

## Contract & protocol to follow
The guidelines below should be followed by everyone in order to have predictable hehavior in server-server communications. The methods for sending files/notifications are defined in file_server_lib.php; include it in your scripts whenever you need to call them.
* a picture has 4 properties(for simplicity we call them the 'picture info'):
  - id: derived from md5 value of the picture file. Required
  - title: title of the picture. Required
  - category: category of a picture, e.g scenery, sports, people etc. Optional
  - desc: the description of a picture. Optional
- the file and info can be stored on your server whatever way you want; but it is recommended to store the file on filesystem, then keep an record in the MySQL database which contains the file's path, timestamp and the picture info
* a server send post request to its peers when:
  - it receives new file from client. In this case it sends the new file together with picture info to other servers
  - file info is updated somehow by it or its client. In this case it notifies and sends picture info to other servers
  - file is deleted somehow by it or its client. In this case it notifies and sends the picture id to other server
* all servers use the same path for receving files and update/delete notifications
  - script for receving files: upload_from_peer.php
  - script for update/delete notifications: notify_from_peer.php
* all servers use the same library methods to send files and send update/delete notifications 
  - method for sending files: send_to_peers
  - method for update notification: notify_update
  - method for delete notification: notify_delete
* see comments in file_server_lib.php  for details

## Things everyone has to implement by self
The following parts can vary from server to server and need to be implementated individually 
* any communications that occurs between server and client
* logic for receiving files from peers (upload_from_peer.php)
* logic for receiving update/delete notifications from peers (notify_from_peer.php)
* logic for storing the file on your server(in DB or on FS; directory and filename etc.)
* logic for keeping track of picture info associated with the files(recommended to keep records in MySQL)

## Directory structure
The shared files(like file_server_lib.php is in the root directory); you need to create a folder for your part. Remember not to commit any confidentials(like password for your MySQL connection etc) to GitHub.

## Helpful docs & resources
* get md5 value of a file: [PHP document for md5_file](http://php.net/manual/en/function.md5-file.php)
