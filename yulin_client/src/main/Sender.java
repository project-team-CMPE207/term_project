package main;

import javafx.application.Platform;
import javafx.concurrent.Task;
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

import java.io.File;
import java.io.IOException;


public class Sender extends Task<Integer> {
    private Controller ctrl;
    private File file;
    private String title;
    private String cat;
    private String desc;

    public Sender(Controller ctrl, File file, String title, String cat, String desc) {
        this.ctrl = ctrl;
        this.file = file;
        this.title = title;
        this.cat = cat;
        this.desc = desc;
    }

    @Override
    public Integer call() {
        CloseableHttpClient httpclient = HttpClients.createDefault();
        try {

            HttpPost httppost = new HttpPost("http://www.yulinye.com/fileserver/upload_from_client.php");

            FileBody bin 		    = new FileBody(this.file);
            StringBody from 		= new StringBody("yulin", ContentType.TEXT_PLAIN);
            StringBody title 		= new StringBody(this.title, ContentType.TEXT_PLAIN);
            StringBody category 	= new StringBody(this.cat, ContentType.TEXT_PLAIN);
            StringBody desc 		= new StringBody(this.desc, ContentType.TEXT_PLAIN);

            HttpEntity reqEntity = MultipartEntityBuilder.create()
                .addPart("file", bin)
                .addPart("from", from)
                .addPart("title", title)
                .addPart("category", category)
                .addPart("desc", desc)
                .build();


            httppost.setEntity(reqEntity);

            System.out.println("executing request " + httppost.getRequestLine());

            CloseableHttpResponse response = httpclient.execute(httppost);
            try {
                System.out.println("----------------------------------------");
                System.out.println(response.getStatusLine());
                HttpEntity resEntity = response.getEntity();
                final String responseString = EntityUtils.toString(resEntity, "UTF-8");
                System.out.println(responseString);

                Platform.runLater(new Runnable() {
                    @Override
                    public void run() {
                        ctrl.setStatus(responseString);
                        ctrl.clearInputs();
                    }
                });
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
        return 1;
    }
}
