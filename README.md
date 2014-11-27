CMPE207 project - by Network Ninjas 
============
# Project scope
- web/mobile applications for picture sharing
- 4 client-server pairs
- 4 servers communicate with each other to sync pictures (the picuture files themselves and the picutre info)

# Contract & protocol to follow
- a picture has 4 properties(for simplicity we call them the 'picture info'):
  - id: derived from md5 value of the file. Required
  - title: title of the picture. Required
  - category: category of a picture, e.g scenery, sports, people etc. Optional
  - desc: the description of a picture. Optional
- the file and info can be stored on your server whatever way you want; but it is recommended to store the file on filesystem, then keep an record in the MySQL database of the file's path, timestamp and the picture info
- a server send post request to its peers when:
  - it receives new file from client. In this case it send ithe new file together with picture info to other servers
  - file info is updated somehow by it or its client. In this case it notifies and sends picture info to other servers
  - file is deleted somehow by it or its client. In this case it notifies and sends the picture id to other server
- all servers have the same script names for receving files and update/delete notifications
- all servers use the same library methods to send files and send update/delete notifications 
- see comments in file_server_lib.php  for details
- 
# Helpful docs & resources for implementation
- get md5 value of a fiel: [PHP document for md5_file](http://php.net/manual/en/function.md5-file.php)
