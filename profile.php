<?php 
include('includes/header.php'); 
require_once('includes/connect.php');
$username = $_GET['username'];
$usersql = "SELECT u.id, u.email, ui.fname, ui.lname, ui.mobile, ui.age, ui.gender, ui.profilepic, ui.bio, ui.fb, ui.twitter, ui.linkedin, ui.blog, ui.website FROM users u JOIN user_info ui WHERE u.id=ui.uid AND u.username=?";
$userresult = $db->prepare($usersql);
$userresult->execute(array($username));
$usercount = $userresult->rowCount();
$userres = $userresult->fetch(PDO::FETCH_ASSOC);

$userid = $userres['id'];
$permsql = "SELECT * FROM user_permission WHERE uid=?";
$permresult = $db->prepare($permsql);
$permresult->execute(array($userid));
$permres = $permresult->fetch(PDO::FETCH_ASSOC);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-md-offset-3">

            <div class="card hovercard">
                <div class="cardheader">

                </div>
                <?php if(!empty($userres['profilepic']) & ($permres['show_pic'] == 1)){ ?>
                <div class="avatar">
                    <img alt="" src="<?php echo $userres['profilepic']; ?>">
                </div>
                <?php } ?>
                <div class="info">
                    <div class="title">
                        <a target="_blank" href="https://codingcyber.org/"><?php if(!empty($userres['fname']) & ($permres['show_fname'] == 1)){ echo $userres['fname']; } ?> <?php if(!empty($userres['lname']) & ($permres['show_lname'] == 1)){ echo $userres['lname']; } ?></a>
                    </div>
                    <div class="desc"><?php if(!empty($userres['gender']) & ($permres['show_gender'] == 1)){ echo $userres['gender']; } ?></div>
                    <div class="desc"><?php if(!empty($userres['age']) & ($permres['show_age'] == 1)){ echo $userres['age']; } ?></div>
                    <div class="desc"><?php if(!empty($userres['bio']) & ($permres['show_bio'] == 1)){ echo $userres['bio']; } ?></div>
                    <div class="desc"> <?php if(!empty($userres['mobile']) & ($permres['show_mobile'] == 1)){ 
                        echo '<i class="fa fa-phone"></i> ';
                        echo $userres['mobile']; } ?></div>
                    <div class="desc"> <?php if(!empty($userres['email']) & ($permres['show_email'] == 1)){ 
                        echo '<i class="fa fa-envelope-o"></i> ';
                        echo $userres['email']; } ?></div>
                </div>
                <div class="bottom">
                    <?php if(!empty($userres['fb']) & ($permres['show_fb'] == 1)){ ?>
                    <a class="btn btn-primary btn-sm" href="<?php echo $userres['fb']; ?>">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <?php } ?>
                    <?php if(!empty($userres['twitter']) & ($permres['show_twitter'] == 1)){ ?>
                    <a class="btn btn-primary btn-twitter btn-sm" href="<?php  echo $userres['twitter']; ?>">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <?php } ?>
                    <?php if(!empty($userres['linkedin']) & ($permres['show_linkedin'] == 1)){ ?>
                    <a class="btn btn-primary btn-sm" href="<?php echo $userres['linkedin']; ?>">
                        <i class="fa fa-linkedin"></i>
                    </a>
                    <?php } ?>
                    <?php if(!empty($userres['blog']) & ($permres['show_blog'] == 1)){ ?>
                    <a class="btn btn-primary btn-sm" href="<?php echo $userres['blog']; ?>">
                        <i class="fa fa-rss"></i>
                    </a>
                    <?php } ?>
                    <?php if(!empty($userres['website']) & ($permres['show_website'] == 1)){ ?>
                    <a class="btn btn-primary btn-sm" href="<?php echo $userres['website']; ?>">
                        <i class="fa fa-globe"></i>
                    </a>
                    <?php } ?>
                </div>
            </div>

        </div>

	</div>
</div>
<style type="text/css">
    

.card {
    padding-top: 20px;
    margin: 10px 0 20px 0;
    background-color: rgba(214, 224, 226, 0.2);
    border-top-width: 0;
    border-bottom-width: 2px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

.card .card-heading {
    padding: 0 20px;
    margin: 0;
}

.card .card-heading.simple {
    font-size: 20px;
    font-weight: 300;
    color: #777;
    border-bottom: 1px solid #e5e5e5;
}

.card .card-heading.image img {
    display: inline-block;
    width: 46px;
    height: 46px;
    margin-right: 15px;
    vertical-align: top;
    border: 0;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
}

.card .card-heading.image .card-heading-header {
    display: inline-block;
    vertical-align: top;
}

.card .card-heading.image .card-heading-header h3 {
    margin: 0;
    font-size: 14px;
    line-height: 16px;
    color: #262626;
}

.card .card-heading.image .card-heading-header span {
    font-size: 12px;
    color: #999999;
}

.card .card-body {
    padding: 0 20px;
    margin-top: 20px;
}

.card .card-media {
    padding: 0 20px;
    margin: 0 -14px;
}

.card .card-media img {
    max-width: 100%;
    max-height: 100%;
}

.card .card-actions {
    min-height: 30px;
    padding: 0 20px 20px 20px;
    margin: 20px 0 0 0;
}

.card .card-comments {
    padding: 20px;
    margin: 0;
    background-color: #f8f8f8;
}

.card .card-comments .comments-collapse-toggle {
    padding: 0;
    margin: 0 20px 12px 20px;
}

.card .card-comments .comments-collapse-toggle a,
.card .card-comments .comments-collapse-toggle span {
    padding-right: 5px;
    overflow: hidden;
    font-size: 12px;
    color: #999;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.card-comments .media-heading {
    font-size: 13px;
    font-weight: bold;
}

.card.people {
    position: relative;
    display: inline-block;
    width: 170px;
    height: 300px;
    padding-top: 0;
    margin-left: 20px;
    overflow: hidden;
    vertical-align: top;
}

.card.people:first-child {
    margin-left: 0;
}

.card.people .card-top {
    position: absolute;
    top: 0;
    left: 0;
    display: inline-block;
    width: 170px;
    height: 150px;
    background-color: #ffffff;
}

.card.people .card-top.green {
    background-color: #53a93f;
}

.card.people .card-top.blue {
    background-color: #427fed;
}

.card.people .card-info {
    position: absolute;
    top: 150px;
    display: inline-block;
    width: 100%;
    height: 101px;
    overflow: hidden;
    background: #ffffff;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

.card.people .card-info .title {
    display: block;
    margin: 8px 14px 0 14px;
    overflow: hidden;
    font-size: 16px;
    font-weight: bold;
    line-height: 18px;
    color: #404040;
}

.card.people .card-info .desc {
    display: block;
    margin: 8px 14px 0 14px;
    overflow: hidden;
    font-size: 12px;
    line-height: 16px;
    color: #737373;
    text-overflow: ellipsis;
}

.card.people .card-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    display: inline-block;
    width: 100%;
    padding: 10px 20px;
    line-height: 29px;
    text-align: center;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

.card.hovercard {
    position: relative;
    padding-top: 0;
    overflow: hidden;
    text-align: center;
    background-color: rgba(214, 224, 226, 0.2);
}

.card.hovercard .cardheader {
    background-size: cover;
    height: 50px;
}

.card.hovercard .avatar {
    position: relative;
    top: -50px;
    margin-bottom: -50px;
}

.card.hovercard .avatar img {
    width: 100px;
    height: 100px;
    max-width: 100px;
    max-height: 100px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    border: 5px solid rgba(255,255,255,0.5);
}

.card.hovercard .info {
    padding: 4px 8px 10px;
}

.card.hovercard .info .title {
    margin-bottom: 4px;
    font-size: 24px;
    line-height: 1;
    color: #262626;
    vertical-align: middle;
}

.card.hovercard .info .desc {
    overflow: hidden;
    font-size: 12px;
    line-height: 20px;
    color: #737373;
    text-overflow: ellipsis;
}

.card.hovercard .bottom {
    padding: 0 20px;
    margin-bottom: 17px;
}

.btn{ border-radius: 50%; width:32px; height:32px; line-height:18px;  }

</style>
<?php include('includes/footer.php'); ?>