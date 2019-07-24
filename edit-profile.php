<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('check-login.php');
include('includes/header.php');
include('includes/navigation.php'); 
require_once('includes/connect.php');
// we will get this userid from session id
$userid = 2;

require_once('includes/smtp.php');

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// fetch the use data from users and user_info tables
// I'll move this query to above POST if condition, so that we can use these values in POST if condition
$usersql = "SELECT u.email, u.password, ui.fname, ui.lname, ui.mobile, ui.age, ui.gender, ui.profilepic, ui.bio, ui.fb, ui.twitter, ui.linkedin, ui.blog, ui.website FROM users u JOIN user_info ui WHERE u.id=ui.uid AND u.id=?";
$userresult = $db->prepare($usersql);
$userresult->execute(array($userid));
$usercount = $userresult->rowCount();
$userres = $userresult->fetch(PDO::FETCH_ASSOC);

if(isset($_POST) & !empty($_POST)){
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
        // update sql query
        $updsql = "UPDATE user_info SET ";
        if(isset($_POST['fname']) & !empty($_POST['fname'])){
            $updsql .= "fname=:fname, ";
            $values['fname'] = $_POST['fname'];
        }
        if(isset($_POST['lname']) & !empty($_POST['lname'])){
            $updsql .= "lname=:lname, ";
            $values['lname'] = $_POST['lname'];
        }
        if(isset($_POST['mobile']) & !empty($_POST['mobile'])){
            $updsql .= "mobile=:mobile, ";
            $values['mobile'] = $_POST['mobile'];
        }
        if(isset($_POST['age']) & !empty($_POST['age'])){
            $updsql .= "age=:age, ";
            $values['age'] = $_POST['age'];
        }
        if(isset($_POST['gender']) & !empty($_POST['gender'])){
            $updsql .= "gender=:gender, ";
            $values['gender'] = $_POST['gender'];
        }
        // if(isset($_POST['profilepic']) & !empty($_POST['profilepic'])){
        //     $updsql .= "profilepic=:profilepic, ";
        //     $values['profilepic'] = $_POST['profilepic'];
        // }
        if(isset($_POST['bio']) & !empty($_POST['bio'])){
            $updsql .= "bio=:bio, ";
            $values['bio'] = $_POST['bio'];
        }
        if(isset($_POST['fb']) & !empty($_POST['fb'])){
            $updsql .= "fb=:fb, ";
            $values['fb'] = $_POST['fb'];
        }
        if(isset($_POST['twitter']) & !empty($_POST['twitter'])){
            $updsql .= "twitter=:twitter, ";
            $values['twitter'] = $_POST['twitter'];
        }
        if(isset($_POST['linkedin']) & !empty($_POST['linkedin'])){
            $updsql .= "linkedin=:linkedin, ";
            $values['linkedin'] = $_POST['linkedin'];
        }
        if(isset($_POST['blog']) & !empty($_POST['blog'])){
            $updsql .= "blog=:blog, ";
            $values['blog'] = $_POST['blog'];
        }
        if(isset($_POST['website']) & !empty($_POST['website'])){
            $updsql .= "website=:website, ";
            $values['website'] = $_POST['website'];
        }
        $updsql .= " updated=NOW() WHERE uid=:uid";
        $updresult = $db->prepare($updsql);
        // we should break these values based on the submitted form values and also the SQL query as constructive SQL query
        $values['uid'] = $userid;
        $updres = $updresult->execute($values);
        if($updres){
            $messages[] = 'Profile Updated';
            // Insert Activity into DB Table - user_activity
            $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
            $actresult = $db->prepare($actsql);
            $values = array(':uid'          => $userid,
                            ':activity'     => 'Profile Updated'
                            );
            $actresult->execute($values);
        }else{
            $errors[] = 'Failed to Update user Profile';
        }

        // checking the password
        if(!empty($_POST['password'])){
            if(empty($_POST['passwordr'])){ $errors[] = 'Repeat Password field is Required';}else{
                // compare both password, if they match. generate the password hash
                if($_POST['password'] == $_POST['passwordr']){
                    if(empty($_POST['passwordcur'])){ $errors[] = 'Current Password field is Required';}
                    // create password hash
                    $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    // we should compare the current password, if it matches. Then we will update the new password in users table and also inserts new record in activity log
                    // we need user count
                    if(($usercount == 1) & empty($errors)){
                        // compare current password with password hash in database
                        if(password_verify($_POST['passwordcur'], $userres['password'])){
                            // update the password with new password
                            $updsql = "UPDATE users SET password=:password, updated=NOW() WHERE id=:id";
                            $updresult = $db->prepare($updsql);
                            $values = array(':password' => $pass_hash,
                                            ':id'       => $userid
                                            );
                            $updres = $updresult->execute($values);
                            if($updres){
                                $messages[] = 'Password Updated';
                                // Insert Activity into DB Table
                                $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
                                $actresult = $db->prepare($actsql);
                                $values = array(':uid'          => $userid,
                                                ':activity'     => 'Password Updated'
                                                );
                                $actresult->execute($values);
                                // send email
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
                                    $mail->Subject = 'Password Updated';
                                    $mail->Body    = "Your Account Password Updated from Dashboard, If you haven't udpated the password, reset your password ASAP.";
                                    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                                    $mail->send();
                                    $messages[] = 'Password Update Confirmation Email Sent';
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            }
                        }else{
                            $errors[] = 'Problem with Current Password';
                        }
                    }
                }else{
                    // error message
                    $errors[] = 'Both Passwords Should Match';
                }
            }
        }
    }
}
// Create CSRF token
$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Update Profie
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
                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <input class="form-control" placeholder="First Name" name="fname" type="text" autofocus value="<?php if(isset($userres['fname'])){ echo $userres['fname']; } ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Last Name" name="lname" value="<?php if(isset($userres['lname'])){ echo $userres['lname']; } ?>">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="E-Mail" name="email" value="<?php if(isset($userres['email'])){ echo $userres['email']; } ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Mobile" name="mobile" type="number" value="<?php if(isset($userres['mobile'])){ echo $userres['mobile']; } ?>">
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="male" <?php if($userres['gender'] == 'male'){ echo "checked"; } ?>>Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="female" <?php if($userres['gender'] == 'female'){ echo "checked"; } ?>>Female
                                    </label>
                                </div>
                                <div class="form-group">
                                    <select name="age" class="form-control">
                                        <option>Select Age</option>
                                        <?php for ($x=0; $x < 50; $x++) {
                                            echo "<option value='$x'";
                                            if($userres['age'] == $x){ echo "selected"; }
                                            echo ">$x</option>";
                                        } ?>
                                        
                                        <option>21</option>
                                        <option>22</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="file" name="profile">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" name="bio" placeholder="Bio"><?php if(isset($userres['bio'])){ echo $userres['bio']; } ?></textarea>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                                    <input name="fb" type="text" class="form-control" placeholder="FaceBook Profile" value="<?php if(isset($userres['fb'])){ echo $userres['fb']; } ?>">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                                    <input name="twitter" type="text" class="form-control" placeholder="Twitter Profile" value="<?php if(isset($userres['twitter'])){ echo $userres['twitter']; } ?>">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-linkedin" aria-hidden="true"></i></span>
                                    <input name="linkedin" type="text" class="form-control" placeholder="Linkedin Profile" value="<?php if(isset($userres['linkedin'])){ echo $userres['linkedin']; } ?>">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-rss" aria-hidden="true"></i></span>
                                    <input name="blog" type="text" class="form-control" placeholder="Blog" value="<?php if(isset($userres['blog'])){ echo $userres['blog']; } ?>">
                                </div>
                                <div class="form-group input-group">
                                    <span class="input-group-addon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                                    <input name="website" type="text" class="form-control" placeholder="Website" value="<?php if(isset($userres['website'])){ echo $userres['website']; } ?>">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="password" type="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="passwordr" type="password" placeholder="Repeat Password">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <input class="form-control" name="passwordcur" type="password" placeholder="Confirm Present Password">
                                </div>

                                <input type="submit" class="btn btn-success btn-lg btn-block" value="Update Profile" />
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
            </form>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<?php include('includes/footer.php'); ?>