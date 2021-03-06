<?php 
session_start();
require_once('includes/connect.php');
include('if-loggedin.php');
include('includes/header.php'); 
$failmax = 5;
if(isset($_POST) & !empty($_POST)){
    if(empty($_POST['email'])){ $errors[] = 'User Name / E-mail field is Required';}
    if(empty($_POST['password'])){ $errors[] = 'Password field is Required';}

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
        // select sql query to check the email id in database
        // updating the sql query to work with email and username with filter_var
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
        if($count == 1){
            // Checking number of failed login attempts
            $failsql = "SELECT * FROM login_fail WHERE uid=? AND loginfailed > NOW() - INTERVAL 60 MINUTE";
            $failresult = $db->prepare($failsql);
            $failresult->execute(array($res['id']));
            $failcount = $failresult->rowCount();
            if($failcount < $failmax){
                $errors[] = 'Number of Failed Attempts' . $failcount;
                // we will check the password & create the session here
                // then comparing the password with password hash
                if(password_verify($_POST['password'], $res['password'])){
                    $messages[] = "Create Session and Redirect user to Members Area";
                    // Insert Activity into DB Table - user_activity
                    $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
                    $actresult = $db->prepare($actsql);
                    $values = array(':uid'          => $res['id'],
                                    ':activity'     => 'User LoggedIn'
                                    );
                    $actresult->execute($values);

                    // update logout time in login_log table, if previous records logout value is blank insert the logout time
                    // select query to get the record with blank logout time for the current logged in user
                    $logsql = "SELECT * FROM login_log WHERE uid=? AND loggedout='0000-00-00 00:00:00' ORDER BY id DESC LIMIT 1";
                    $logresult = $db->prepare($logsql);
                    $logresult->execute(array($res['id']));
                    $logcount = $logresult->rowCount();
                    $logres = $logresult->fetch(PDO::FETCH_ASSOC);
                    if($logcount == 1){
                        // update the loggout time
                        $logoutsql = "UPDATE login_log SET loggedout=NOW() WHERE id=:id";
                        $logoutresult = $db->prepare($logoutsql);
                        $values = array(':id'       => $logres['id']);
                        $logoutresult->execute($values);
                    }

                    // Insert Login timestamps into DB Table - login_log
                    $loginsql = "INSERT INTO login_log (uid, loggedin) VALUES (:uid, NOW())";
                    $loginresult = $db->prepare($loginsql);
                    $values = array(':uid'          => $res['id'] );
                    $loginresult->execute($values);

                    // regenerate session id
                    session_regenerate_id();
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $res['id'];
                    $_SESSION['last_login'] = time();

                    // redirect the user to members area/dashboard page

                    header('location:index.php');
                }else{
                    // Insert Failed Login Attempt to user_activity table
                    $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
                    $actresult = $db->prepare($actsql);
                    $values = array(':uid'          => $res['id'],
                                    ':activity'     => 'User LogIn Failed'
                                    );
                    $actresult->execute($values);

                    // Insert Failed Login timestamp in login_fail table
                    $loginfailsql = "INSERT INTO login_fail (uid) VALUES (:uid)";
                    $loginfailresult = $db->prepare($loginfailsql);
                    $values = array(':uid'          => $res['id'] );
                    $loginfailresult->execute($values);

                    // calculate the number of remaining attempts
                    $remainingattempts = $failmax - $failcount;

                    $errors[] = "Invalid User Name / E-Mail & Password Combination";
                    $errors[] = "You have {$remainingattempts} login attempts remaining, otherwise you will be blocked for 60 minutes";
                }
            }else{
                $errors[] = 'You are blocked for 60 minutes to login, retry after some time';
            }

            
        }else{
            $errors[] = "User Name / E-Mail Not Valid";
            // Insert Failed Login Attempt to user_activity table
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
                <h3 class="panel-title">Please Sign In</h3>
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
                            <input class="form-control" placeholder="E-mail" name="email" type="text" autofocus  value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password">
                        </div>
                        <!-- <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me">Remember Me
                            </label>
                        </div> -->
                        <!-- Change this to a button or input when using this as a form -->
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
