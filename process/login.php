<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 4/12/13
 * Time: 2:51 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('../system/generic.inc.php');
$driver = new driver;
ini_set('date.timezone','Africa/Nairobi');
if(isset($_POST['ulogin']))
    $uname	= $_POST['uname'];
    $upass	= $_POST['upass'];
    $uname	= $driver->clean($uname,1);
    $upass	= $driver->clean($upass,1);
    if(empty($uname) || empty($upass))
        die('<span class="error">Enter your login details</span>');
    if(!$driver->num_and_alpha($uname) || !$driver->num_and_alpha($upass))
        die('<span class="error">Wrong username or password!</span>');

        $uname	=	mysql_real_escape_string($uname);
        $upass	= 	mysql_real_escape_string($upass);
        $upass	=	md5($upass);
        $sql="SELECT * FROM users WHERE uname='$uname' AND upassword='$upass'";
        $user = $driver->perform_request($sql) or /* code to log errors */ die('SQL ERROR <span class="error">'.mysql_error().'</span>');
        if($driver->numRows($user)>1 or $driver->numRows($user)<=0 )
            die('<span class="error"><b>Wrong username or password</b></span>');
            $user_details	= $driver->load_data($user,MYSQL_ASSOC);
            $user_details	=	extract($user_details);
            $time = date('H:i:s');
            $date = date('Y-m-d');
            $query = "UPDATE users SET onoroff='1' WHERE id='$id'";
            $query_two="INSERT INTO activities VALUES('','$id','Logged in','$time','$date')";
            $driver->perform_request($query) or die('<p class="error">Can not update activity.<br/>'.mysql_error().'</p>');
            $driver->perform_request($query_two) or die('<p class="error">Can not update activity.<br/>'.mysql_error().'</p>');
            session_start();
            $_SESSION['login']	=	true;
            $_SESSION['name'] 	=  $name;
            $_SESSION['id']     =   $id;
            $_SESSION['lvl']	=  $model;
            switch($model):
                CASE 1:
                header('location:../');
                break;
                CASE 2:
                header('location:../admin.php');
                break;
                CASE 3:
                header('location:../clients.php');
                break;
                default:
                header('location:../index.php');
                break;
            endSwitch;
?>