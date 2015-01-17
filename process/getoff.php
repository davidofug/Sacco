<?php
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/generic.inc.php');
$driver = new driver;
session_start();
$date = date('Y-m-d');
$time = date('H:i:s');
$query = "UPDATE users SET onoroff='0' WHERE id='{$_SESSION['id']}'";
$query_two="INSERT INTO activities VALUES('','{$_SESSION['id']}','Logged out','$time','$date')";
$driver->perform_request($query) or die('<p class="error">Can not update activity.<br/>'.mysql_error().'</p>');
$driver->perform_request($query_two) or die('<p class="error">Can not update activity.<br/>'.mysql_error().'</p>');
	unset($_SESSION);
	session_destroy();
	header('Location:../');
?>
	