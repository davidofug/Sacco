<?php
	include('system/db_obj.php');
	$driver = new driver;
	$link = $driver->con_To_Server(SERVER,USERNAME,PASSWORD,DATABASE);	
    $qury = "SELECT id FROM transactions";
    $result = mysql_query($qury) or die(mysql_error());
	$num	= mysql_num_rows($result);
    $row	= mysql_fetch_row($result);
    echo 'number of rows: '.$num;
	$startNum	=	($num-1);
	$query = "SELECT c_balance FROM transactions ORDER BY id DESC LIMIT 1";
	$reslt = mysql_query($query) or die(mysql_error());
	$row1  = mysql_fetch_assoc($reslt);
	echo '<br/>Cash balance: '.$row1['c_balance'];
?>