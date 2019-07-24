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
    if(empty($_POST['email'])){ $errors[] = 'User Name field is Required';}

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
        $sql = "SELECT * FROM users WHERE ";
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $sql .= "email=?";
        }else{
            $sql .= "username=?";
        }
        $sql .= " AND activate=1";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        $userid = $res['id'];
        if($count == 1){
            $messages[] = 'User Name / Email exists, create reset token and send email';
            // Generating and Inserting Activation Token in DB Table - user_active
            $reset_token = md5($res['username']).time();
            $resetsql = "INSERT INTO password_reset (uid, reset_token) VALUES (:uid, :reset_token)";
            $resetresult = $db->prepare($resetsql);
            $values = array(':uid'              => $userid,
                            ':reset_token'     => $reset_token
                            );
            $resetresult->execute($values);

            // Inserting Activity into DB Table
            $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
            $actresult = $db->prepare($actsql);
            $values = array(':uid'          => $userid,
                            ':activity'     => 'Password Reset Intiated'
                            );
            $actresult->execute($values);

            // Send Email to User
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
                // TODO : update recipient email with dynamic email
                $mail->addAddress('vivek@codingcyber.com', 'Vivek Vengala');     // Add a recipient

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Reset Your Password';
                $mail->Body    = "{$url}reset-password.php?key={$reset_token}&id={$userid}</b>";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                $messages[] = 'Password Reset Email Sent, Follow the Instructions';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }else{
            $errors[] = 'Your Account is not available with in our activated accounts, please check with site Admin!';
        }
    }
}   
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Reset Password</h3>
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
                            <input class="form-control" placeholder="E-mail or User Name" name="email" type="text" autofocus value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Reset Password" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
