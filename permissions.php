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
    }else{
        // insert the permissions into table with user id
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
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Display First Name</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_fname" value="1" <?php if($res['show_fname'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_fname" value="0" <?php if($res['show_fname'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Last Name</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_lname" value="1" <?php if($res['show_lname'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_lname" value="0" <?php if($res['show_lname'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Mobile</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_mobile" value="1" <?php if($res['show_mobile'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_mobile" value="0" <?php if($res['show_mobile'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Age</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_age" value="1" <?php if($res['show_age'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_age" value="0" <?php if($res['show_age'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Gender</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_gender" value="1" <?php if($res['show_gender'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_gender" value="0" <?php if($res['show_gender'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Pic</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_pic" value="1" <?php if($res['show_pic'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_pic" value="0" <?php if($res['show_pic'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Bio</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_bio" value="1" <?php if($res['show_bio'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_bio" value="0" <?php if($res['show_bio'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Display Facebook</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_fb" value="1" <?php if($res['show_fb'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_fb" value="0" <?php if($res['show_fb'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Twitter</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_twitter" value="1" <?php if($res['show_twitter'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_twitter" value="0" <?php if($res['show_twitter'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Linkedin</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_linkedin" value="1" <?php if($res['show_linkedin'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_linkedin" value="0" <?php if($res['show_linkedin'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Blog</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_blog" value="1" <?php if($res['show_blog'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_blog" value="0" <?php if($res['show_blog'] == 0){ echo "checked"; } ?>>No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Display Website</label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_website" value="1" <?php if($res['show_website'] == 1){ echo "checked"; } ?>>Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="show_website" value="0" <?php if($res['show_website'] == 0){ echo "checked"; } ?>>No
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