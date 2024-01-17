<?php 

session_start();
$message = "";
if(isset($_POST['submit'])) {
    $user = $_POST['user'];
    $password = $_POST['password'];
    
        
    //Überprüfung des Passworts
    if ($user === "ds" && $password === "Ds192837465.") {
        $_SESSION['user'] = $user;
        header("Location:" . "admin/index.php");
        exit;
    } else {
        $message = "E-Mail oder Passwort war ungültig<br>";
    }
    
}
?>


<?php
    require_once("header.php");
?>

    <div class="container">
       
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center logo-margin ">
              <img src="assets/img/logo.png" alt=""/>
                </div>
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">                  
                    <div class="panel-heading">
                        <h3 style = "margin-top: 100px; margin-bottom:25px; text-align: center; color:white"class="panel-title">Login</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method= "post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User name" name="user" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <input type= "submit" name="submit" value="Login" class="btn btn-lg btn-primary btn-block"></a>
                                <h6 style = "margin-top: 20px; text-align: center;"> <?= $message; ?> </h6>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
require_once("footer.php");
?>