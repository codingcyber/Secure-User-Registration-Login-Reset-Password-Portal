<?php 
session_start();
include('includes/header.php'); 
require_once('includes/connect.php');
if(isset($_POST) & !empty($_POST)){
    print_r($_POST);
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
        $result = $db->prepare($sql);
        $result->execute(array($_POST['email']));
        $count = $result->rowCount();
        $res = $result->fetch(PDO::FETCH_ASSOC);
        if($count == 1){
            // then comparing the password with password hash
            if(password_verify($_POST['password'], $res['password'])){
                $messages[] = "Create Session and Redirect user to Members Area";
            }else{
                $errors[] = "User Name / E-Mail & Password Combination not Working";
            }
        }else{
            $errors[] = "User Name / E-Mail Not Valid";
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
                        <div class="checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="Remember Me">Remember Me
                            </label>
                        </div>
                        <!-- Change this to a button or input when using this as a form -->
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
