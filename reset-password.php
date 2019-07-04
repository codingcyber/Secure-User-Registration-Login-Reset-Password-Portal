<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
include('includes/header.php'); 
require_once('includes/connect.php');
require_once('includes/smtp.php');

$url = "http://localhost/Secure-User-Registration-Login-Reset-Password-Portal/";

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
if(isset($_POST) & !empty($_POST)){
    if(empty($_POST['password'])){ $errors[] = 'Password field is Required';}else{
        if(empty($_POST['passwordr'])){ $errors[] = 'Repeat Password field is Required';}else{
            // compare both password, if they match. generate the password hash
            if($_POST['password'] == $_POST['passwordr']){
                // create password hash
                $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }else{
                // error message
                $errors[] = 'Both Passwords Should Match';
            }
        }
    }

    // CSRF Token Validation
    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problem with CSRF Token Verification";
        }
    }else{
        $errors[] = "Problem with CSRF Token Validation";
    }

    // CSRF Token Time Validation
    $max_time = 60*60*24;
    if(isset($_SESSION['csrf_token_time'])){
        $token_time = $_SESSION['csrf_token_time'];
        if(($token_time + $max_time) >= time()){
        }else{
            $errors[] = "CSRF Token Expired";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }else{
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }

    if(empty($errors)){
        
    }
}   
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

// fetch the user details from database and display those details in disabled input fields, username & email
$sql = "SELECT * FROM password_reset WHERE reset_token=:reset_token AND uid=:uid";
$result = $db->prepare($sql);
$values = array(':reset_token'     => $_GET['key'],
                ':uid'        => $_GET['id']
                );
$result->execute($values);
$count = $result->rowCount();
if($count == 1){
    $messages[] = "Get the User Details from users table";
    // Select SQL query to fetch user details from users table using user id
}else{
    $errors[] = "There is some problem with Reset Token, Contact Site Admin!";
}
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Update Password</h3>
            </div>
            <div class="panel-body">
                <?php
                    if(!empty($messages)){
                        echo "<div class='alert alert-success'>";
                        foreach ($messages as $message) {
                            echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;". $message ."<br>";
                        }
                        echo "</div>";
                    }
                ?>
                <?php
                    if(!empty($errors)){
                        echo "<div class='alert alert-danger'>";
                        foreach ($errors as $error) {
                            echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;". $error ."<br>";
                        }
                        echo "</div>";
                    }
                ?>
                <form role="form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="username" type="text" autofocus disabled value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" disabled value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" >
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password">
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Change Password" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
