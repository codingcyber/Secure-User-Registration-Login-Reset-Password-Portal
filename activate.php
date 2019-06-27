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
        $messages[] = 'Preparing Your Account for First Time Login';
        // adding activity in user_activity table
        $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
        $actresult = $db->prepare($actsql);
        $values = array(':uid'          => $_GET['id'],
                        ':activity'     => 'User Registered'
                        );
        $actresult->execute($values);
        $messages[] = 'Adding User Registration Log Entry';
        
        // send confirmation email to user
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = $smtphost;  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $smtpuser;                     // SMTP username
            $mail->Password   = $smtppass;                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('test@example.com', 'Vivek Vengala');
            // update recipient email with dynamic email
            $mail->addAddress('vivek@codingcyber.com', 'Vivek Vengala');     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Account Activated';
            $mail->Body    = "Your Account Activated, Please Login</b>";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $messages[] = 'Activation Email Sent, Follow the Instructions';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
}else{
    $errors[] = "Failed to Activate Your Account, Check with Site Admin!";
}
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