<?php
    require_once("dBconn.php");
    session_start();
?>


<?php 

    $if_video = -1;

    if (isset($_POST["showPhotoSubmit"])){
        $sql =  "SELECT media_name,if_video FROM gallery WHERE if_video = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([0]);
        $if_video = 0;    
    }elseif (isset($_POST["showVideoSubmit"])){
        $sql =  "SELECT media_name,if_video FROM gallery WHERE if_video = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([1]);
        $if_video = 1;
    }elseif (isset($_POST["showYoutube"])) {
        $sql =  "SELECT media_name,if_video FROM gallery WHERE if_video = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([2]);
        $if_video = 2;
    }else{
        $sql =  "SELECT media_name,if_video FROM gallery";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $if_video = -1;
    }
    $result = $stmt->fetchAll();

?>



<?php

  require_once('header.php');
?>

<!-- end header -->

</header>


<div class="backgro_mh">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heding">
                    <h2>Gallerie</h2>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="gallery" class="Gallery" style="margin-top:0px; padding-top: 40px;padding-bottom: 40px;">
    <form action="" method="post">
        <div class="container">
            <div class="col-12">
                <div class="row" style="align-items: center;margin-left: 45%;margin-bottom: 20px;">
                    <button type="submit" name="showAll" class="btn btn-primary">All</button>
                    <span>/</span>
                    <button type="submit" name="showPhotoSubmit" class="btn btn-warning">Bilder</button>
                    <span>/</span>
                    <button type="submit" name="showVideoSubmit" class="btn btn-success">Videos</button>
                    <span>/</span>
                    <button type="submit" name="showYoutube" class="btn btn-danger">Youtube</button>
                </div>
            </div>
        </div>
    </form>

    <div class="container">
        <div class="row display_boxflex">

            <?php foreach($result as $key=>$value): ?>
            <?php if($value[1] == 0): ?>
            <div class="col-3">
                <div class="Gallery_img" style="margin-bottom:20px;">

                    <figure><a href="<?php echo "upload_images/" . $value[0] ?>"><img style="width:100%; vertical-align:middle; object-fit:fill; height:200px;"
                                    src=" <?php echo "upload_images/" . $value[0] ?>" alt="#"></a>
                    </figure>

                </div>

            </div>
            <?php elseif($value[1] == 1): ?>
            <div class="col-3">
                <div class="Gallery_img" style="margin-bottom:20px;">

                    <video style="width:100%; vertical-align:middle; object-fit:fill; height:200px;" controls>
                        <source src="<?= "upload_videos/" . $value[0] ?>" alt="">
                    </video>

                </div>

            </div>
            <?php elseif($value[1] == 2): ?>
            <div class="col-3">
                <div class="Gallery_img" style="margin-bottom:20px;">

                    <iframe class="embed-responsive-item" src="<?= "//" . $value[0];?>"
                        style="width:100%; vertical-align:middle; object-fit:fill; height:200px;">
                    </iframe>

                </div>

            </div>
            <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>


</div>
</div>

<!-- end Gallery -->




<?php
    require_once("footer.php");
?>