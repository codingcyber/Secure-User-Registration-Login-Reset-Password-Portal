<?php 
include('includes/header.php'); 
require_once('includes/connect.php');
if(isset($_POST) & !empty($_POST)){
    print_r($_POST);
    // PHP Form Validations
    if(empty($_POST['uname'])){ $errors[] = 'User Name field is Required';}
    if(empty($_POST['email'])){ $errors[] = 'E-mail field is Required';}
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
    // password will be password hash
    // Insert values into users table
    if(empty($errors)){
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
            }
        }
    }
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
                <form role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="uname" type="text" autofocus value="<?php if(isset($_POST['uname'])){ echo $_POST['uname']; } ?>">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>">
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
                        <input type="submit" class="btn btn-lg btn-success btn-block" value="Register" />
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>