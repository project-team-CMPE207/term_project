import java.io.*;
import java.util.Scanner;

import org.apache.http.HttpEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.ContentType;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.util.EntityUtils;

class HttpFileClient {

  public HttpFileClient() {
    System.out.println("client created!");
  }

  public void handleInput() {
    Scanner userInput = new Scanner(System.in);
    String filePath;
    File file;

    System.out.println("Please type the path and file name you want to send.\nTo close this conneciton, enter \"close\"\n");
   
    
    //example of filePath C:\\Users\\Ken\\Documents\\CMPE207\\http-file-upload\\img3.jpg
    while ((filePath = userInput.nextLine()) != null) {
    	filePath=filePath.replace("\\","/");
    	System.out.println(filePath);
    	
      if (userInput.equals("close")) {
        System.out.println("You have requested close the connection.");
        System.exit(0);
      }
      
      if ((file = readFile(filePath)) != null) {
        sendFile(file);
      } else {
        System.out.println("The path you entered is not valid. Please try again.");
      }
      
    }  
  }

  String fileName;
  public File readFile(String filePath) {
    
    fileName = filePath.substring(filePath.lastIndexOf("/") + 1, filePath.length());
    //String directory = filePath.substring(0, filePath.lastIndexOf("\") + 1);
    System.out.println(filePath);
    File file = new File(filePath);
   
    if (file.isFile()) {
        return file;
    } else {
    	return null;
    }
    

  }

  public void sendFile(File file) {
    CloseableHttpClient httpclient = HttpClients.createDefault();
    try {
      HttpPost httppost = new HttpPost("http://www.skctech.net/PHPJavaServer.php");

      FileBody bin = new FileBody(file);
      StringBody name = new StringBody(fileName, ContentType.TEXT_PLAIN);
            
      HttpEntity reqEntity = MultipartEntityBuilder.create()
    		  .addPart("file", bin)
    		  .addPart("name", name)
    		  .build();


      httppost.setEntity(reqEntity);

      System.out.println("executing request " + httppost.getRequestLine());
      CloseableHttpResponse response = httpclient.execute(httppost);
      try {
        System.out.println("----------------------------------------");
        System.out.println(response.getStatusLine());       
        HttpEntity resEntity = response.getEntity();
        System.out.println(EntityUtils.toString(resEntity));
        EntityUtils.consume(resEntity);
      } catch (IOException e) {
        e.printStackTrace();
      } finally {
        response.close();
      }
    } catch (IOException e) {
        e.printStackTrace();
    } finally {
      try {
        httpclient.close();
      } catch (IOException e) {
        e.printStackTrace();
      }
    }

  }

  public static void main(String[] args) {
    HttpFileClient client = new HttpFileClient();
    client.handleInput();
  }

}
