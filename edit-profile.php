<?php 
//session_start();
include('check-login.php');
include('includes/header.php');
include('includes/navigation.php'); 
require_once('includes/connect.php');
// we will get this userid from session id
$userid = 2;

if(isset($_POST) & !empty($_POST)){
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
    }else{
        $errors[] = 'Failed to Update user Profile';
    }
}

// fetch the use data from users and user_info tables
$usersql = "SELECT u.email, ui.fname, ui.lname, ui.mobile, ui.age, ui.gender, ui.profilepic, ui.bio, ui.fb, ui.twitter, ui.linkedin, ui.blog, ui.website FROM users u JOIN user_info ui WHERE u.id=ui.uid AND u.id=?";
$userresult = $db->prepare($usersql);
$userresult->execute(array($userid));
$userres = $userresult->fetch(PDO::FETCH_ASSOC);
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
                                    <input class="form-control" name="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="passwordr" placeholder="Repeat Password">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm Present Password">
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