<?php
    require_once("../dBconn.php");
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location:". "../login.php" );
        exit();
    }
?>

<?php

$message1= "";

if(isset($_POST["termin"])) {
        $ort            = $_POST["ort"];
        $date           = $_POST["app_date"];
        $id             = $_POST["id"];
        $new_name       = "";
        if(isset($_FILES['fileToUpload1']) && !empty($_FILES['fileToUpload1']['name'])){
            $target_dir     = "../upload_images/";
            $name           = htmlspecialchars(basename($_FILES["fileToUpload1"]["name"]));
            $imageFileType  = strtolower(pathinfo($name,PATHINFO_EXTENSION));
            $new_name       = "ds"."-".rand()."-".$name;
            $target_file    = $target_dir . $new_name;
            $uploadOk       = 1;
            
            
            // Check if image file is a actual image or fake image
            // With getimagesize function, we can obtain width, height, data type and width * height 
            // informations. If it returns true , it means it is an image(!!!not a video!!!).
            if($_FILES["fileToUpload1"]["tmp_name"] == ""){
                $check = false;
            }else{
                $check = getimagesize($_FILES["fileToUpload1"]["tmp_name"]);
            }
            
            if($check !== false) {
                //$message1 .= "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $message1 .= "Datei ist kein Bild.";
                $uploadOk = 0;
            }
    
            //Webp bakilacak.....!!!!!!!!!
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" &&
                $imageFileType != "gif" && $imageFileType != "tiff" && $imageFileType != "psd" && 
                $imageFileType != "pdf" && $imageFileType != "eps" && $imageFileType != "ai" && 
                $imageFileType != "indd" && $imageFileType != "raw" && $imageFileType != "webp" ) {
                
                    $message1 .= " Ihre Datei hat kein geeignetes Format.";
                    $uploadOk = 0;
            }
    
            // Check file size
            if ($_FILES["fileToUpload1"]["size"] > 50000000) {
                $message1 .= " Ihre Datei ist zu groß.";
                $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $message1 .= " Ihre Datei wurde nicht hochgeladen.";
            // if everything is ok, try to upload file ***tmp_name = C:\xampp\tmp\php1472.tmp***
            } else {
                if (move_uploaded_file($_FILES["fileToUpload1"]["tmp_name"], $target_file)) { 
                    $message1 .= " Ihre Datei ". $new_name . " wurde hochgeladen.";
                    $sql = "UPDATE appointment SET ort=?, appointment_date=?, image_name=? WHERE id=?";
                    $stmt= $pdo->prepare($sql);
                    $stmt->execute([$ort, $date, $new_name, $id]);
                    header("Location:". "table.php" );
                    exit();

                } else {
                $message1 .= " Beim Hochladen Ihrer Datei ist ein Fehler aufgetreten.";
                }           
            }


        }else{
            $new_name  = $_POST["image"];
            $message1 .= " Ihre Datei ". $new_name . " wurde hochgeladen.";
            $sql = "UPDATE appointment SET ort=?, appointment_date=?, image_name=? WHERE id=?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$ort, $date, $new_name, $id]);
            header("Location:". "table.php" );
            exit();

        }
        
}

    $id      = $_POST["edit"];
    $sql     =  "SELECT * FROM appointment where id=?";
    $stmt    = $conn->prepare($sql);
    $stmt->execute(array($id));
    $result  = $stmt->fetchAll();
?>

<?php
require_once("header.php");
?>
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Termin aktualisieren</h1>
                <h1 class="page-subhead-line">DS-Musik-Trio Admin Panel. </h1>

            </div>
        </div>
        <!-- /. ROW  -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        Termin
                    </div>
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Ort</label>
                                <input class="form-control" type="text" name="ort" value="<?= $result[0][1]; ?>"
                                    required>
                                </input>
                                <p class="help-block">Bitte Ort für den Termin eingeben.</p>
                            </div>
                            <div class="form-group">
                                <label>Datum</label>
                                <input class="form-control" name="app_date" type="date" value="<?= $result[0][2]; ?>"
                                    required>
                                </input>
                                <p class="help-block">Bitte Datum für den Termin eingeben.</p>
                            </div>
                            <div class="form-group">
                                <label>Bild</label>

                                <div style="display:flex">
                                    <input style="float:left;margin:0px !important" type="file" name="fileToUpload1"
                                        value="<?= $result[0][3]; ?>" id="fileToUpload1">
                                    </input>
                                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img
                                            src="<?= "../upload_images/" . $result[0][3]; ?>" alt="" />
                                    </div>
                                </div>
                                <p></p>
                                <hr>
                            </div>

                            <button type="submit" class="btn btn-success" name="termin">Aktualisieren </button>
                            <p><?= $message1; ?></p>
                            <input type="hidden" id="id" name="id" value="<?= $result[0][0]; ?>">
                            <input type="hidden" id="image" name="image" value="<?= $result[0][3]; ?>">

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/.ROW-->

    </div>
    <!-- /. PAGE INNER  -->
</div>

<!-- /. WRAPPER  -->
<div id="footer-sec">
    &copy; 2014 YourCompany | Design By : <a href="http://www.binarytheme.com/" target="_blank">BinaryTheme.com</a>
</div>
<!-- /. FOOTER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>


</body>

</html>