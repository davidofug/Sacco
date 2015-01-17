<?php
defined('_FEXEC') or ('Access denied');
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/generic.inc.php');
$driver	= new driver;
		$name	=	trim(strip_tags(stripslashes(ucwords($_POST['name']))));
		$acno	=	trim(strip_tags(stripslashes($_POST['accno'])));
		$age	=	trim(strip_tags(stripslashes($_POST['age'])));
		$sex	=	trim(strip_tags(stripslashes($_POST['sex'])));
		$idno	=	trim(strip_tags(stripslashes($_POST['idno'])));
        $compno	=	trim(strip_tags(stripslashes($_POST['compno'])));
		$phno	=	trim(strip_tags(stripslashes($_POST['phno'])));
		$addr	=	mysql_real_escape_string(trim(strip_tags(stripslashes($_POST['addr']))));
		$pin	=	date('His').'-'.$acno;
		$pass	=	hash('haval256,3',$pin);
		$date	=	date('Y-m-d');
		$chkacn	= "SELECT * FROM clients,users WHERE clients.acnumber='$acno' OR clients.compno='$compno' OR users.uname='$acno'";
    if($query	= $driver->perform_request($chkacn)): //Check wether the client to be registered is already registered.
        if(mysql_num_rows($query)>0):
            $client = $driver->load_data($query);
                if($acno == $client['acnumber']):
                echo '<p class="error">The client <b>'.$client['name'].' is already registered with that account number('.$acno.')<br/> Close the window and try again!</p>';
                elseif($compno == $client['compno']):
                    echo '<p class="error">The client <b>'.$client['name'].' is already registered with that Computer number number('.$compno.')<br/> Close the window and try again!</p>';
               else:
                        echo '<p class="notice"> Client records already registered</p>';
                endif;
      else:
             $reg="INSERT INTO clients VALUES('','$date','$compno','$name','$acno','$age','$sex','$idno','$phno','$addr')";
             $user = "INSERT INTO users VALUES('','$date','$name','$acno','$pass','3','','','1')";
            $result	= ($driver->perform_request($reg) && $driver->perform_request($user))?'<p class="suxs"> '.$name.' registered successfully.<br/>USERNAME =>'.$acno.'<br/> PIN NUMBER =>'.$pin.'</p>':'SQL ERROR: '.mysql_error();
            echo $result;
        endif;
    else:
        echo '<p class="error">SQL ERROR While registering client <br/>'.mysql_error().'</p>';
    endif;
?>