<?php
// check admin user or not for admin pages
require_once('includes/connect.php');
include('check-login.php');
$uid = $_SESSION['id'];
$sql = "SELECT * FROM users WHERE id=?";
//$sql .= " AND role='admin'";
$result = $db->prepare($sql);
$result->execute(array($uid));
$res = $result->fetch(PDO::FETCH_ASSOC);
// check count
if($res['role'] == 'admin'){
	// keep the user on the same page
}else{
	// redirect the user to dashboard page
	header('location: index.php');
}