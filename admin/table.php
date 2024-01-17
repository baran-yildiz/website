<?php
    require_once("../dBconn.php");
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location:". "../login.php" );
        exit();
    }
    $monate = array('','Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');
?>

<?php
    if(isset($_POST["delete"])){
        $id = $_POST["delete"];
        
        $sql = "DELETE FROM appointment WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($id));
       
    }
?>

<?php
$sql    =   "SELECT * FROM appointment";
$stmt   =   $conn->prepare($sql);
$stmt   ->  execute();
$result =   $stmt->fetchAll();

?>

<?php
    require_once("header.php");
?>
<!-- /. NAV SIDE  -->
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-head-line">Data Table</h1>
                <h1 class="page-subhead-line">DS-Musik-Trio Admin Panel. </h1>

            </div>
        </div>
        <!-- /. ROW  -->
        <div class="row">
            <div class="col-xs-12">
                <!--    Context Classes  -->
                <div class="panel panel-default">

                    <div class="panel-heading">
                        Termine
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive-xs">
                            <table class="table" style="width:100%; table-layout:fixed;">
                                <thead>
                                    <tr>
                                        <th style="width:10%;">#</th>
                                        <th style="width:30%;">Ort</th>
                                        <th style="width:15%;">Datum</th>
                                        <th style="width:25%;">Bearbeiten</th>
                                        <th style="width:20%;">Löschen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    <?php foreach($result as $key=>$value): ?>

                                    <tr class="<?= $value[4] ?>">
                                        <td><?= $no += 1; ?></td>
                                        <td style="word-wrap:break-word;">
                                            <?= $value[1] ?>
                                        </td>
                                        <td><?= date("j", strtotime($value[2])) . " " . $monate[date("n", strtotime($value[2]))]. " ".
                                         date("Y", strtotime($value[2]));?></td>
                                        <form method="post" action="form1.php">
                                        <td><button value="<?= $value[0];?>" name="edit" type="submit" style="font-size:0.9em;
                                        width:100%;" class="btn btn-warning">Bearbeiten</button></td>
                                        </form>
                                        <form method="post" action="">
                                            <td><button value="<?= $value[0];?>" name="delete" type="submit" style="font-size:0.9em;
                                        width:100%;" class="btn btn-danger">Löschen</button></td>
                                        </form>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--  end  Context Classes  -->
            </div>
        </div>
        <!-- /. ROW  -->

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
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>


</body>

</html>