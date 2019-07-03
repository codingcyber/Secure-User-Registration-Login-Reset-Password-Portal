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

// update logout time in login_log table, if previous records logout value is blank insert the logout time
// select query to get the record with blank logout time for the current logged in user
$sql = "SELECT * FROM login_log WHERE uid=? AND loggedout='0000-00-00 00:00:00' ORDER BY id DESC LIMIT 1";
$result = $db->prepare($sql);
$result->execute(array($uid));
$count = $result->rowCount();
$res = $result->fetch(PDO::FETCH_ASSOC);
if($count == 1){
    // update the loggout time
    $logoutsql = "UPDATE login_log SET loggedout=NOW() WHERE id=:id";
    $logoutresult = $db->prepare($logoutsql);
    $values = array(':id'       => $res['id']);
    $logoutresult->execute($values);
}
// redirect user to login page
//header('location:login.php');
?>