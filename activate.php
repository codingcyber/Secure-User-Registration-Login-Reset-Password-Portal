<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
include('includes/header.php'); 
require_once('includes/connect.php');
require_once('includes/smtp.php');

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
// check the activation key in user_active table
$sql = "SELECT * FROM user_active WHERE active_token=:active_token AND uid=:uid";
$result = $db->prepare($sql);
$values = array(':active_token' => $_GET['key'],
                ':uid'          => $_GET['id']
                );
$result->execute($values);
$count = $result->rowCount();
if($count == 1){
    $messages[] = 'Account Exists';
    // if the activation key exists, make the user as active and remove the key
    $updsql = "UPDATE users SET activate=:activate, updated=NOW() WHERE id=:id";
    $updresult = $db->prepare($updsql);
    $values = array(':activate' => 1,
                    ':id'       => $_GET['id']
                    );
    $updresult->execute($values);
    if($updresult){
        $messages[] = 'Account Activated Successfully';
        // delete activation key record from user_active table
        $delsql = "DELETE FROM user_active WHERE active_token=?";
        $delresult = $db->prepare($delsql);
        $delresult->execute(array($_GET['key'])); 
    }
}
// send confirmation email to user
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please Register</h3>
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
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>