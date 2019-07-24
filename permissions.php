<?php 
include('check-login.php');
include('includes/header.php');
include('includes/navigation.php'); 
require_once('includes/connect.php');
$userid = 2;
if(isset($_POST) & !empty($_POST)){
    // check permission for this user exist - create or update the permissions
    $sql = "SELECT * FROM user_permission WHERE uid=?";
    $result = $db->prepare($sql);
    $result->execute(array($userid));
    $count = $result->rowCount();
    if($count == 1){
        // update the permissions
        $updsql = "UPDATE user_permission SET  show_fname=:show_fname, show_lname=:show_lname, show_mobile=:show_mobile, show_email=:show_email, show_age=:show_age, show_gender=:show_gender, show_pic=:show_pic, show_bio=:show_bio, show_fb=:show_fb, show_twitter=:show_twitter, show_linkedin=:show_linkedin, show_blog=:show_blog, show_website=:show_website, updated=NOW() WHERE uid=:uid";
        $updresult = $db->prepare($updsql);
        $values = array(':uid'              => $userid,
                        ':show_fname'       => $_POST['fname'],
                        ':show_lname'       => $_POST['lname'],
                        ':show_mobile'      => $_POST['mobile'],
                        ':show_email'       => $_POST['email'],
                        ':show_age'         => $_POST['age'],
                        ':show_gender'      => $_POST['gender'],
                        ':show_pic'         => $_POST['pic'],
                        ':show_bio'         => $_POST['bio'],
                        ':show_fb'          => $_POST['fb'],
                        ':show_twitter'     => $_POST['twitter'],
                        ':show_linkedin'    => $_POST['linkedin'],
                        ':show_blog'        => $_POST['blog'],
                        ':show_website'     => $_POST['website'],
                        );
        $updres = $updresult->execute($values);
        if($updres){
            $messages[] = "Updated the User Permissions";
            $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
            $actresult = $db->prepare($actsql);
            $values = array(':uid'          => $userid,
                            ':activity'     => 'User Permissions Updated'
                            );
            $actresult->execute($values);
        }
    }else{
        // insert the permissions into table with user id
        $insql = "INSERT INTO user_permission (uid, show_fname, show_lname, show_mobile, show_email, show_age, show_gender, show_pic, show_bio, show_fb, show_twitter, show_linkedin, show_blog, show_website) VALUES (:uid, :show_fname, :show_lname, :show_mobile, :show_email, :show_age, :show_gender, :show_pic, :show_bio, :show_fb, :show_twitter, :show_linkedin, :show_blog, :show_website)";
        $inresult = $db->prepare($insql);
        $values = array(':uid'              => $userid,
                        ':show_fname'       => $_POST['fname'],
                        ':show_lname'       => $_POST['lname'],
                        ':show_mobile'      => $_POST['mobile'],
                        ':show_email'       => $_POST['email'],
                        ':show_age'         => $_POST['age'],
                        ':show_gender'      => $_POST['gender'],
                        ':show_pic'         => $_POST['pic'],
                        ':show_bio'         => $_POST['bio'],
                        ':show_fb'          => $_POST['fb'],
                        ':show_twitter'     => $_POST['twitter'],
                        ':show_linkedin'    => $_POST['linkedin'],
                        ':show_blog'        => $_POST['blog'],
                        ':show_website'     => $_POST['website'],
                        );
        $inres = $inresult->execute($values);
        if($inres){
            $messages[] = "Inserted the User Permissions for First Time";
            $actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
            $actresult = $db->prepare($actsql);
            $values = array(':uid'          => $userid,
                            ':activity'     => 'User Permissions Created'
                            );
            $actresult->execute($values);
        }
    }
}
// fetch the permission from table and display them
$sql = "SELECT * FROM user_permission WHERE uid=?";
$result = $db->prepare($sql);
$result->execute(array($userid));
$res = $result->fetch(PDO::FETCH_ASSOC);
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Information Display Preferences
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
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Display First Name</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="fname" value="1" <?php if($res['show_fname'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="fname" value="0" <?php if($res['show_fname'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Last Name</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="lname" value="1" <?php if($res['show_lname'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="lname" value="0" <?php if($res['show_lname'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Mobile</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="mobile" value="1" <?php if($res['show_mobile'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="mobile" value="0" <?php if($res['show_mobile'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display E-Mail</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="email" value="1" <?php if($res['show_email'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="email" value="0" <?php if($res['show_email'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Age</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="age" value="1" <?php if($res['show_age'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="age" value="0" <?php if($res['show_age'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Gender</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="1" <?php if($res['show_gender'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" value="0" <?php if($res['show_gender'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Pic</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="pic" value="1" <?php if($res['show_pic'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="pic" value="0" <?php if($res['show_pic'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Bio</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="bio" value="1" <?php if($res['show_bio'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="bio" value="0" <?php if($res['show_bio'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Display Facebook</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="fb" value="1" <?php if($res['show_fb'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="fb" value="0" <?php if($res['show_fb'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Twitter</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="twitter" value="1" <?php if($res['show_twitter'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="twitter" value="0" <?php if($res['show_twitter'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Linkedin</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="linkedin" value="1" <?php if($res['show_linkedin'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="linkedin" value="0" <?php if($res['show_linkedin'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Blog</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="blog" value="1" <?php if($res['show_blog'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="blog" value="0" <?php if($res['show_blog'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Website</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="website" value="1" <?php if($res['show_website'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="website" value="0" <?php if($res['show_website'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Update Permissions" />
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                    </div>
                    </form>
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