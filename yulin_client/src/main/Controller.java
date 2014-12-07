package main;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;

import org.apache.http.HttpEntity;
import org.apache.http.client.methods.CloseableHttpResponse;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.ContentType;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.MultipartEntityBuilder;
import org.apache.http.util.EntityUtils;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

public class Controller implements Initializable {

    @FXML
    private Button browseBtn;

    @FXML
    private TextField   titleText;

    @FXML
    private TextField   catText;

    @FXML
    private TextArea descText;

    @FXML
    private ImageView previewImg;

    @FXML
    private Label status;

    private File tmpFile;

    @FXML
    public void handleClickBrowse() throws FileNotFoundException {
        FileChooser fileChooser = new FileChooser();
        configureFileChooser(fileChooser);
        tmpFile = fileChooser.showOpenDialog( browseBtn.getScene().getWindow());
        Image tmpImg = new Image(new FileInputStream(tmpFile), 300.0, 190.0, false, true);
        previewImg.setImage(tmpImg);
        setStatus("file is selected: "+tmpFile.toString());

    }

    public void handleClickUpload() {
        setStatus("start sending file...");
        System.out.println(titleText.getText());
        System.out.println(catText.getText());
        System.out.println(descText.getText());

        sendFile(tmpFile, titleText.getText(), catText.getText(), descText.getText());
        clearInputs();
    }

    private static void configureFileChooser(final FileChooser fileChooser) {
        fileChooser.setTitle("View Pictures");
        fileChooser.setInitialDirectory(
            new File(System.getProperty("user.home"))
        );
    }

    private void sendFile(File file, String titleP, String catP, String descP) {
        CloseableHttpClient httpclient = HttpClients.createDefault();
        try {

            HttpPost httppost = new HttpPost("http://www.yulinye.com/fileserver/upload_from_client.php");

            FileBody   bin 		    = new FileBody(file);
            StringBody from 		= new StringBody("yulin", ContentType.TEXT_PLAIN);
            StringBody title 		= new StringBody(titleP, ContentType.TEXT_PLAIN);
            StringBody category 	= new StringBody(catP, ContentType.TEXT_PLAIN);
            StringBody desc 		= new StringBody(descP, ContentType.TEXT_PLAIN);

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
                String responseString = EntityUtils.toString(resEntity, "UTF-8");
                System.out.println(responseString);
                setStatus(responseString);
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

    private void setStatus(String s) {
        status.setText("Status: "+s);
    }

    private void clearStatus() {
        status.setText("");
    }

    private void clearInputs() {
        titleText.clear();
        catText.clear();
        descText.clear();
        setImagePlaceHolder();
    }

    private void setImagePlaceHolder(){
        Image placeholder = null;
        try {
            placeholder = new Image(new FileInputStream(new File("/home/ryan/projects/207/file_client/src/main/placeholder.jpg")), 300.0, 190.0, true, true);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
        previewImg.setImage(placeholder);
    }

    @Override
    public void initialize(URL location, ResourceBundle resources) {
        clearStatus();
        clearInputs();
    }
}
