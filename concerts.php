<?php
require_once("dBconn.php");
$monate = array('','Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
$sql    =  "SELECT * FROM appointment";
$stmt   =   $conn->prepare($sql);
$stmt   ->  execute();
$result = $stmt->fetchAll();
$total_page = ceil(count($result)/10);
$page = $_GET["page"];

if (!isset($_GET["page"])){
    $sql    =  "SELECT * FROM appointment ORDER BY appointment_date DESC LIMIT 0,10";
    $stmt   =   $conn->prepare($sql);
    $stmt   ->  execute();
}elseif(isset($_GET["page"])){
    $page = $_GET["page"];  
    $sql = "SELECT * FROM appointment ORDER BY appointment_date DESC LIMIT " . ($page - 1)*10 . "," . " 10";
    $stmt   =   $conn->prepare($sql);
    $stmt   ->  execute();
    
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
                    <h2>Termine</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- upcoming -->
<?php foreach($result as $key=>$value): ?>
<div id="upcoming" class="upcoming" style="padding-top:0px;">
    <div class="container-fluid padding_left3">
        <div class="row display_boxflex" style="border-bottom: 5px;">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <p style="color: red; font-size: 16px; font-weight: 450;"><?=date("j", strtotime($value[2])) . " " . $monate[date("n", strtotime($value[2]))]. " ".
                                         date("Y", strtotime($value[2]));?></p>

            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <p style="color: black; font-size: 16px; font-weight: 450;"><?= $value[1] ?></p>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3">
                <div class="upcomimg" style="width:auto; height:50px;margin:10px; float:right;">
                    <figure><img style="width:auto; height:50px;" src="<?= $value[3] ?>" alt="#" /></figure>
                </div>
            </div>
        </div>
        <hr style="border-top:2px solid #0ed1f0;margin:0px;">
    </div>
</div>
<?php endforeach;?>
<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item"><a class="page-link"
                href="?page=<?php if($page == 1){echo $page=1;}else{ $page1 = $page-1; echo $page1;} ?>">Previous</a>
        </li>
        <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
        <li class="page-item"><a class="page-link" href="?page=2">2</a></li>
        <li class="page-item"><a class="page-link" href="?page=3">3</a></li>
        <li class="page-item"><a class="page-link" href="?page=4">4</a></li>
        <li class="page-item"><a class="page-link" href="?page=5">5</a></li>
        <li class="page-item"><a class="page-link"
                href="?page=<?php if($page == $total_page){echo $total_page;}else{$page1 = $page+1; echo $page1;}?>">Next</a>
        </li>
    </ul>
</nav>
<!-- end upcoming -->


<?php
    require_once("footer.php");
?>