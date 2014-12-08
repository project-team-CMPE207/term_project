package main;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Label;
import javafx.scene.control.TextArea;
import javafx.scene.control.TextField;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.stage.FileChooser;


import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.net.URL;
import java.util.ResourceBundle;

public class Controller implements Initializable {

    @FXML
    private TextField titleText;

    @FXML
    private TextField catText;

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
        tmpFile = fileChooser.showOpenDialog( titleText.getScene().getWindow());
        if (tmpFile != null) {
            Image tmpImg = new Image(new FileInputStream(tmpFile), 300.0, 190.0, false, true);
            previewImg.setImage(tmpImg);
            setStatus("file is selected: "+tmpFile.toString());
        }
    }

    public void handleClickUpload() {
        if (tmpFile != null) {
            setStatus("start sending file...");
            sendFile(tmpFile, titleText.getText(), catText.getText(), descText.getText());

        }
    }

    private static void configureFileChooser(final FileChooser fileChooser) {
        fileChooser.setTitle("View Pictures");
        fileChooser.setInitialDirectory(
            new File(System.getProperty("user.home"))
        );
    }

    private void sendFile(File file, String title, String cat, String desc) {
        Sender worker = new Sender(this, file, title, cat, desc);
        new Thread(worker).start();
    }

    public void setStatus(String s) {
        status.setText("Status: "+s);
    }

    private void clearStatus() {
        status.setText("");
    }

    public void clearInputs() {
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
