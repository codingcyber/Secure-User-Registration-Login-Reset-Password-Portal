<?php
// check the page
//echo basename($_SERVER['PHP_SELF']);
if((basename($_SERVER['PHP_SELF']) == 'login.php') || (basename($_SERVER['PHP_SELF']) == 'register.php')){
	if(isset($_SESSION['id']) & !empty($_SESSION['id'])){
		//echo "redirect to index.php page";
		header('location: index.php');
	}
}