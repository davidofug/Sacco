<?php
defined('_FEXEC') or ('Access denied');
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/db_obj.php');
include_once('../system/generic.inc.php');
$driver	= new driver;
$newcash = 0;
$date	= date('Y-m-d');
$tid	= date('YmdHis'); //Computed transaction id		
$type		= $driver->clean($_POST['accounttype'],1,1); //Sanitize the account type
$account	= $driver->clean($_POST['account'],1,1); //Sanitize the user entered account number
$amount	 	= $driver->clean($_POST['amount'],1,1); //Sanitize the user enetered amount
$accountant	= $driver->clean($_SESSION['name'],1,1);//The system user processing the transaction
if(empty($type) or empty($amount)):
	echo '<p class="error">Please enter complete fields as required! <a href="#">Back</a></p>';
	exit();
elseif(!$driver->digits($amount)):
	echo '<p class="error">Digits required for the Account and Amount fields! <a href="#">Back</a></p>';
	exit();
elseif($type == 3 && empty($account)):
	echo '<p class="error">Please specify member\'s account <a href="#">Back</a></p>';
	exit();
else:
	$newcash = curCash()+$amount; // New cash
	$cash	=	curCash();
	if($account == -1){
		$buf_to_prs = "INSERT INTO transactions	VALUES('$tid','$date','','','RESERVE','DEPOSIT','CASH','2','','','','','$amount','$cash','$accountant')";
		$reserve = "INSERT INTO reserve VALUES('','$date','4','$amount')";	
	}else if($account == -2){
			$trs = "INSERT INTO transactions	VALUES('$tid','$date','','','O/CASH','DEPOSIT','CASH','2','','','','','$amount','$newcash','$accountant')";		
	}else{
		if(!$driver->digits($account)):
			echo '<p class="error">Digits required for the Account number! <a href="#">Back</a></p>';
		exit();
		endif;
		$get_member  ="SELECT * FROM clients WHERE 	acnumber='$account'";
		if($result = $driver->perform_request($get_member)):
			if($driver->numRows($result)>0){
					$data	=	 $driver->load_data($result, MYSQL_ASSOC);
					$name	=	$data['name'];
					$buf_to_prs  =	"INSERT INTO transactions VALUES('$tid','$date','','','$account','$name','DEPOSIT','','','','','','$amount','$newcash','$accountant')";
					$client_trans = "INSERT INTO client_transactions VALUES('$tid','$date','','$account','DEPOSIT','$name','','','','','','','','$amount')";
			}else{
					echo '<p class="error">The account number ( '.$account.') you entered, doesn\'t exist.</p>';
					exit();
			}
		else:
			echo '<h3 class="error">Error encountered.</h3>';
			echo '<p class="error">'.MySQL_error().'<br/><a href="#">Go Back</a></p>';
			exit();
		endif;		
	}
$allvals	= 	'';
foreach($_POST as $k=>$val):
	$allvals .=$val;
endforeach;
$formHash= md5($allvals);
$allowAction=true;
if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
	$allowAction = false;
}	
if($allowAction==true):
	if(isset($buf_to_prs) && isset($reserve)){
		if($driver->perform_request($buf_to_prs) and $driver->perform_request($reserve)){
			echo  '<p class="success">Deposit to reserve account is completed.</p>';
		}else{
			echo '<h3 class="error">Error encountered.</h3>';
			echo '<p class="error">'.MySQL_error().'<br/><a href="#">Go Back</a></p>';
			exit();
				}
	}else if(isset($buf_to_prs) and isset($client_trans)){
		if($driver->perform_request($buf_to_prs) && $driver->perform_request($client_trans)){
			echo  '<p class="success">Deposit to member account ('.$account.') is completed.</p>';	
		}else{
			echo '<h3 class="error">Error encountered.<
			echo '<h3 class="error">Error encountered.</h/h3>';
			echo '<p class="error">'.MySQL_error().'<br/><a href="#">Go Back</a></p>';
			exit();
		}
}else if(isset($trs)){
		if($driver->perform_request($trs)){
			echo  '<p class="success">Deposit to Operating balance is completed.</p>';
		}else{3>';
			echo '<p class="error">'.MySQL_error().'<br/><a href="#">Go Back</a></p>';
			exit();
		}
}else{
	echo '<h3 class="error">Error encountered.</h3>';
	echo '<p class="error">No transaction set. Inform administrator for troubleshooting.<a href="#">Go back</a></p>';
}
	$_SESSION['formHash'][$_POST['formid']] = $formHash;
else:
	echo '<p class="error">Duplicate transactions not allowed.</p>';
endif;		
endif;
?>