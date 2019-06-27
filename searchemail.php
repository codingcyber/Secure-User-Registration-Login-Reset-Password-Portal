<?php
require_once('includes/connect.php');
$sql = "SELECT * FROM users WHERE email=?";
$result = $db->prepare($sql);
$result->execute(array($_GET['search']));
$count = $result->rowCount();
if($count >= 1){
    echo "<div class='alert alert-danger'><span class='glyphicon glyphicon-remove'></span>&nbsp; E-Mail Exists in Database";
}else{
	echo "<div class='alert alert-success'><span class='glyphicon glyphicon-ok'></span>&nbsp; E-Mail Available";
}
?>