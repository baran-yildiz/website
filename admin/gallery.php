<?php
    require_once("../dBconn.php");
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location:". "../login.php" );
        exit();
    }
?>

<?php
    $message1 = "";
    $message2 = "";
    $message3 = "";
    $link     = "";
    $ifvideo  = 0;
    //Adding new photos...
    if(isset($_POST["submit1"])) {
        $target_dir = "../upload_images/";
        $name = htmlspecialchars(basename($_FILES["fileToUpload1"]["name"]));
        $imageFileType = strtolower(pathinfo($name,PATHINFO_EXTENSION));
        $new_name = "ds"."-".rand()."-".$name;
        $target_file = $target_dir . $new_name;
        $uploadOk = 1;
        
        
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
                $sql = "INSERT INTO gallery (media_name, media_address, if_video)
                VALUES (?,?,?)";
                $stmt= $conn->prepare($sql);
                $stmt->execute([$new_name, $target_file, 0]);
            } else {
            $message1 .= " Beim Hochladen Ihrer Datei ist ein Fehler aufgetreten.";
            }           
        }
    }

    //Adding new videos...
    if(isset($_POST["submit2"])) {
        $target_dir = "../upload_videos/";
        $name = htmlspecialchars(basename($_FILES["fileToUpload2"]["name"]));
        $videoFileType = strtolower(pathinfo($name,PATHINFO_EXTENSION));
        $new_name = "ds"."-".rand()."-".$name;
        $target_file = $target_dir . $new_name;
        $uploadOk = 1;
        
        // Check if size of video file bigger than 10 MB or not
        $check = filesize($_FILES["fileToUpload2"]["tmp_name"]);
      // Check file size
      if ($check > 1000000000) {
        $message2 .= " Ihre Datei ist zu groß.";
        $uploadOk = 0;
      }
      $videoFileType = strtoupper($videoFileType);
      if ($videoFileType != "MP4" && $videoFileType != "WEBM" && $videoFileType != "AVI" &&
            $videoFileType != "MPG" && $videoFileType != "MP2" && $videoFileType != "MPEG" && 
            $videoFileType != "MPE" && $videoFileType != "MPV" && $videoFileType != "OGG"&& 
            $videoFileType != "MP4" && $videoFileType != "M4P" && $videoFileType != "M4V" &&
            $videoFileType != "WMV" && $videoFileType != "MOV" && $videoFileType != "QT"
            && $videoFileType != "FLV" && $videoFileType != "SWF" && $videoFileType != "AVCHD") {
            
                $message2 .= " Ihre Datei ist keine Video.";
                $uploadOk = 0;
      }


      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
         $message2 .= " Ihre Datei wurde nicht hochgeladen.";
      // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
          $message2 .= " Die Datei ". $new_name . " wurde hochgeladen.";
          $ifvideo   = 1;
          $sql = "INSERT INTO gallery (media_name, media_address, if_video)
                  VALUES (?,?,?)";
                  $stmt= $conn->prepare($sql);
                  $stmt->execute([$new_name, $target_file, 1]);
        } 
      }
    }

    //Adding new  youtube videos...
    if(isset($_POST["submit3"])) {

        $number1 = strpos($_POST["youtube_text"], "www");
        echo $number1;
        $link = substr($_POST["youtube_text"], $number1);
        
        $link = strtok($link, '"');
        $ifvideo   = 2;
        $sql = "INSERT INTO gallery (media_name, media_address, if_video)
                VALUES (?,?,?)";
                $stmt= $conn->prepare($sql);
                $stmt->execute([$link, $link, 2]);
        
    }
    
    
?>

<?php
    if (isset($_POST["deleteVideo"])) {
        //print_r($_POST);    
        $filename = $_POST["deleteVideo"];
        $filename1 = substr($filename, 2); 
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/DS-Music" . $filename1;
        if (file_exists($file_path)) {
            echo "ife girdi....";
          chmod( $file_path, 0777 );
          unlink( $file_path );
          $sql = "DELETE FROM gallery WHERE media_address = ?";
          $stmt = $conn->prepare($sql);
          $stmt->execute(array($filename));
          //echo 'File '.$filename.' has been deleted';
        } else {
          //echo 'Could not delete '.$filename.', file does not exist';
        }
    }elseif(isset($_POST["deletePhoto"])) {
        $filename = $_POST["deletePhoto"];
        $filename1 = substr($filename, 2); 
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/DS-Music" .$filename1;
        if (file_exists($file_path)) {
          unlink($file_path);
          $sql = "DELETE FROM gallery WHERE media_address = ?";
          $stmt = $conn->prepare($sql);
          $stmt->execute(array($filename));
          //echo 'File '.$filename.' has been deleted';
        } else {
          //echo 'Could not delete '.$filename.', file does not exist';
        }
    }elseif(isset($_POST["deleteLink"])) {
        $filename = $_POST["deleteLink"];
        
        $sql = "DELETE FROM gallery WHERE media_address = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($filename));
        
        
    }
?>

<?php 
    if (isset($_POST["showPhotoSubmit"])){
        $sql =  "SELECT media_address,if_video FROM gallery WHERE if_video = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([0]);    
    }elseif (isset($_POST["showVideoSubmit"])){
        $sql =  "SELECT media_address,if_video FROM gallery WHERE if_video = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([1]);
    }else {
        $sql =  "SELECT media_address,if_video FROM gallery";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    $result = $stmt->fetchAll();
?>

<?php
require_once("header.php");
?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Gallerie</h1>
            </div>
        </div>
        <!-- /. ROW  -->


        <div class="row">
            <div class="content-body" style="width: 100% !important">
                <div class="container" style="width:100% !important">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row"
                            style="height:auto; width:100% !important; border: 2px solid deepskyblue;padding:30px;margin:15px">
                            <div class="col-sm-6">
                                <h3 style="margin-bottom: 50px; margin-left:30%;">Bild Hinzufügen</h3>

                                <div style="display:flex">
                                    <input style="float:left;margin:0px !important" type="file" name="fileToUpload1"
                                        id="fileToUpload1">
                                    <input class="btn btn-primary" type="submit" value="Upload Image" name="submit1">
                                </div>
                                <p><?= $message1; ?></p>
                                <hr>
                            </div>
                            <div class="col-sm-6" style="border-left:1px solid deepskyblue;">
                                <h3 style="margin-bottom: 50px; margin-left:30%;">Video Hinzufügen</h3>
                                <div style="display:flex">
                                    <input style="float:left;margin:0px !important" type="file" name="fileToUpload2"
                                        id="fileToUpload2">
                                    <input class="btn btn-primary" type="submit" value="Upload Video" name="submit2">
                                </div>
                                <p><?= $message2; ?></p>
                                <hr>
                            </div>
                        </div>
                        <div class="row"
                            style="height:auto; width:100% !important; border: 2px solid deepskyblue;padding:30px;margin:15px">
                            <div class="col-sm-12">
                                <h3 style="margin-bottom: 50px; margin-left:30%;">Youtube Video Hinzufügen</h3>
                                <div style="display:flex">
                                    <form>
                                        <input style="float:left;margin:0px !important; width:75%;" type="text"
                                            name="youtube_text" id="youtube_text">
                                        <input style="margin-left: 5%;" class="btn btn-primary" type="submit"
                                            value="Link Video" name="submit3">
                                </div>
                                <p><?= $message3; ?></p>
                                <hr>
                    </form>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>



<div id="port-folio">
    <div class="row ">
        <form action="" method="post">
            <ul id="filters">
                <li><button type="submit" name="showAll">All </button></li>
                <li><span class="filter active">/</span></li>
                <li><button type="submit" name="showVideoSubmit">Video </button></li>
                <li><span class="filter">/</span></li>
                <li><button type="submit" name="showPhotoSubmit">Bilder </button></li>
            </ul>
        </form>
        <form action="" method="post">
            <?php foreach($result as $key=>$value): ?>
            <?php if($value[1] == 0): ?>
            <div class="col-md-4 ">
                <div style="margin-top:15px;">
                    <img src="<?= $value[0]; ?>" class="img-responsive " alt=""
                        style="width:100%; vertical-align:middle; object-fit:fill; height:200px;" />
                    <div class="overlay" style="display:grid;">
                        <button value="<?= $value[0];?>" name="deletePhoto" type="submit"
                            class="btn btn-danger">Löschen</button>
                    </div>
                </div>
            </div>
            <?php elseif($value[1] == 1): ?>
            <div class="col-md-4 ">
                <div style="margin-top:15px;height:auto">
                    <video style="width:100%; vertical-align:middle; object-fit:fill; height:200px;">
                        <source src="<?=  $value[0]; ?>" alt="">
                    </video>
                    <div class="overlay" style="display:grid;">
                        <button value="<?= $value[0];?>" name="deleteVideo" type="submit"
                            class="btn btn-danger">Entfernen</button>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="col-md-4 ">
                <div style="margin-top:15px;height:auto">
                    <div class="embed-responsive embed-responsive-16by9">
                        <?php  echo "//" . $value[0];?>
                        <iframe class="embed-responsive-item" src="<?= "//" . $value[0];?>"
                            style="width:100%; vertical-align:middle; object-fit:fill; height:250px;">
                        </iframe>
                    </div>
                    <div class="overlay" style="display:grid;">
                        <button value="<?= $value[0];?>" name="deleteLink" type="submit"
                            class="btn btn-danger">Löschen</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
        </form>
    </div>
</div>


</div>
<!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->

<div id="footer-sec">
    &copy; 2014 YourCompany | Design By : <a href="http://www.binarytheme.com/" target="_blank">BinaryTheme.com</a>
</div>


<!-- /. FOOTER  -->
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<!-- PAGE LEVEL SCRIPTS -->
<script src="assets/js/jquery.prettyPhoto.js"></script>
<script src="assets/js/jquery.mixitup.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<!-- CUSTOM Gallery Call SCRIPTS -->
<script src="assets/js/galleryCustom.js"></script>

</body>

</html>