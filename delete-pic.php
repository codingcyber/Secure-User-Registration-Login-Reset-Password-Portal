<?php
include('check-login.php');
require_once('includes/connect.php');
$userid = 2;
// fetch profile pic from user_info database table, then delete the image & update the value with empty value in database
$picsql = "SELECT profilepic FROM user_info WHERE uid=?";
$picresult = $db->prepare($picsql);
$picresult->execute(array($userid));
$picres = $picresult->fetch(PDO::FETCH_ASSOC);
if(isset($picres['profilepic']) & !empty($picres['profilepic'])){
	if(file_exists($picres['profilepic'])){
		// delete the image, if the file exists
		if(unlink($picres['profilepic'])){
			$updsql = "UPDATE user_info SET profilepic='', updated=NOW() WHERE id=?";
            $updresult = $db->prepare($updsql);
            $updres = $updresult->execute(array($userid));
            if($updres){
            	// redirect user to edit-profile.php file
            	header("location: edit-profile.php");
            }
		}
	}else{
		// if the file does not exits, update the database with empty value
		$updsql = "UPDATE user_info SET profilepic='', updated=NOW() WHERE id=?";
        $updresult = $db->prepare($updsql);
        $updres = $updresult->execute(array($userid));
        if($updres){
        	// redirect user to edit-profile.php file
        	header("location: edit-profile.php");
        }
	}
}
?>