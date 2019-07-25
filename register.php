<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();
require_once('includes/connect.php');
include('if-loggedin.php');
include('includes/header.php'); 
include('recaptchalib.php'); 
require_once('includes/smtp.php');

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$url = "http://localhost/Secure-User-Registration-Login-Reset-Password-Portal/";
$secret = "6LfVZn0UAAAAAFOgvkH4AqGD8NwNy0KvxMFBkUL_";
$response = null;
$reCaptcha = new ReCaptcha($secret);

if(isset($_POST) & !empty($_POST)){
    // PHP Form Validations
    if(empty($_POST['uname'])){ $errors[] = 'User Name field is Required';}else{
        // check username is unique with db query
        $sql = "SELECT * FROM users WHERE username=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['uname']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "User Name already exists in database";
        }
    }
    if(empty($_POST['email'])){ $errors[] = 'E-mail field is Required';}else{
        // check email is unique with db query
        $sql = "SELECT * FROM users WHERE email=?";
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "E-mail already exists in database";
        }
    }
    if(empty($_POST['mobile'])){ $errors[] = 'Mobile field is Required';}
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

    // password will be password hash
    // Insert values into users table
    if(empty($errors)){
        if($_POST['g-recaptcha-response']){
            $response = $reCaptcha->verifyResponse(
                        $_SERVER['REMOTE_ADDR'],
                        $_POST['g-recaptcha-response']
                );
        }
        if($response != null && $response->success){
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $result = $db->prepare($sql);
            $values = array(':username'     => $_POST['uname'],
                            ':email'        => $_POST['email'],
                            ':password'     => $pass_hash,
                            );
            $res = $result->execute($values);
            if($res){
                $messages[] = 'User Registered';
                // get the id from the last insert query and insert a new record into  user_info table mobile number column
                $userid = $db->lastInsertID();
                $uisql = "INSERT INTO user_info (uid, mobile) VALUES (:uid, :mobile)";
                $uiresult = $db->prepare($uisql);
                $values = array(':uid'      => $userid,
                                ':mobile'   => $_POST['mobile']
                                );
                $uires = $uiresult->execute($values);
                if($uires){
                    $messages[] = "Added Users Meta Information";
                    // Insert Activity into DB Table - user_activity
                    $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
                    $actresult = $db->prepare($actsql);
                    $values = array(':uid'          => $userid,
                                    ':activity'     => 'User Registered'
                                    );
                    $actresult->execute($values);
                    $messages[] = 'Adding User Registration Log Entry';

                    // Generating and Inserting Activation Token in DB Table - user_active
                    $active_token = md5($_POST['uname']).time();
                    $activesql = "INSERT INTO user_active (uid, active_token) VALUES (:uid, :active_token)";
                    $activeresult = $db->prepare($activesql);
                    $values = array(':uid'              => $userid,
                                    ':active_token'     => $active_token
                                    );
                    $activeresult->execute($values);

                    // send email to registered user
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
                        $mail->Subject = 'Verify Your Email';
                        $mail->Body    = "{$url}activate.php?key={$active_token}&id={$userid}</b>";
                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                        $mail->send();
                        $messages[] = 'Activation Email Sent, Follow the Instructions';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
        }else{
            $errors[] = "Problem with Captcha";
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
                <form role="form" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="uname" id="uname" type="text" autofocus value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                            <span id="unameresults"></span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" id="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
                            <span id="emailresults"></span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Mobile" name="mobile" type="text" value="<?php if(isset($_POST['mobile'])){ echo $_POST['mobile']; } ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" >
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password">
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                         <div class="g-recaptcha" data-sitekey="6LfVZn0UAAAAABGnxSUrSALkOTyQgZqcJE8FctZ3"></div>
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Register" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var unameresults = document.getElementById("unameresults");
    var uname = document.getElementById("uname");

    function getUserNameResults(){
        var unameVal = uname.value;

        if(unameVal.length < 1){
            unameresults.style.display='none';
        }

        console.log('unameVal : ' + unameVal);
        var xhr = new XMLHttpRequest();
        var url = 'searchusername.php?search=' + unameVal;
        // open function
        xhr.open('GET', url, true);

        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
                var text = xhr.responseText;
                // console.log('response from searchresults.php : ' + xhr.responseText);
                unameresults.innerHTML = text;
                unameresults.style.display='block';
            }
        }
        xhr.send();
    }

    uname.addEventListener("input", getUserNameResults);
</script>
<script type="text/javascript">
    var emailresults = document.getElementById("emailresults");
    var email = document.getElementById("email");

    function getEmailResults(){
        var emailVal = email.value;

        if(emailVal.length < 1){
            emailresults.style.display='none';
        }

        console.log('emailVal : ' + emailVal);
        var xhr = new XMLHttpRequest();
        var url = 'searchemail.php?search=' + emailVal;
        // open function
        xhr.open('GET', url, true);

        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 && xhr.status == 200){
                var text = xhr.responseText;
                // console.log('response from searchresults.php : ' + xhr.responseText);
                emailresults.innerHTML = text;
                emailresults.style.display='block';
            }
        }
        xhr.send();
    }

    email.addEventListener("input", getEmailResults);
</script>
<?php include('includes/footer.php'); ?>