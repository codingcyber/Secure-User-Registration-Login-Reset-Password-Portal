<?php 
include('includes/header.php'); 
require_once('includes/connect.php');
if(isset($_POST) & !empty($_POST)){
    print_r($_POST);
    // password will be password hash
    // Insert values into users table
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $result = $db->prepare($sql);
    $values = array(':username'     => $_POST['uname'],
                    ':email'        => $_POST['email'],
                    ':password'     => $_POST['password'],
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
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Please Register</h3>
            </div>
            <div class="panel-body">
                <?php print_r($messages); ?>
                <form role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="User Name" name="uname" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Mobile" name="mobile" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Repeat Password" name="passwordr" type="password" value="">
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