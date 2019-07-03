<?php
session_start();
$uid = $_SESSION['id'];
session_destroy();
require_once('includes/connect.php');
// Adding Logout Activity in user_activity table
$actsql = "INSERT INTO user_activity (uid, activity) VALUES (:uid, :activity)";
$actresult = $db->prepare($actsql);
$values = array(':uid'          => $uid,
                ':activity'     => 'User LogOut'
                );
$actresult->execute($values);
// redirect user to login page
//header('location:login.php');
?>