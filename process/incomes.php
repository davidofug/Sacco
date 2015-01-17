<?php
defined('_FEXEC') or ('Access denied');
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/db_obj.php');
include_once('../system/generic.inc.php');
$driver	= new driver;
//Start processing the expense transaction
$source			=	$driver->clean($_POST['source'],1,1); //Sanitize the income source value 
$particulars	=	$driver->clean($_POST['particulars'],1,1); //Sanitize the income particulars value
$amount			=	$driver->clean($_POST['amount'],1,1); //Sanitize the income amount value	
if($amount<=0): //Check if the expense amount entered is less or equal to zero(0)
	echo '<span class="error">Error: Income amount can not be '.$amount.' amount</span>'; //Print an error if expense amount is less or equal to zero(0)
else: 
usleep(200); //Let the script execution pause(sleep) for a few milliseconds
$curcsh	=	curCash(); //Check for the current cash balance
if($amount>$curcsh){
	echo '<span class="error">Error: '.$amount.'.00/= expense amount entered exceeds '.$curcsh.'.00/= current cash</span>';
}else{
	usleep(200); //Let the script execution pause(sleep) for a few milliseconds
	$newcsh		=	curCash()+$amount; //Compute the current cash
	$id			=	date('YmdHis'); //Computed transaction id
	$date		=	date("Y-m-d"); //Compute the current date
	$accountant	=	$driver->clean(mysql_real_escape_string($_SESSION['name']),1,1);//The system user processing the transaction
	$exp_to_ts	=	"INSERT INTO transactions VALUES('$id','$date','','','$source','$particulars','INCOME','','','','','','$amount','$newcsh','$accountant')";
	$exp		=	"INSERT INTO income VALUES('','$date','$source','$particulars','$amount','$accountant')";
	$allvals	= 	'';
		foreach($_POST as $k=>$val):
			$allvals .=$val;
		endforeach;
		
		$formHash	= md5($allvals);
		$allowAction	=	true;
		
		if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
			$allowAction = false;
		}
		 if(curCash()<=minCash()){
			die('<p class="error">Can not make any transactions due to low cash balance!<br/>Advised to top up balance.</p>');										
		 }else if(curCash()<=0){
			die('<p class="error">Can not make any transactions due to low cash balance!<br/>Advised to top up balance.</p>');										
		 }else{
		 	 if($allowAction==true):
				try{
					mysql_query('BEGIN');
					$driver->perform_request($exp_to_ts);
					$driver->perform_request($exp);
					throw new Exception(mysql_error());
					mysql_query('COMMIT');
					echo '<p class="suxs">INCOME TRANSACATION COMPLETED SUCCESSFULLY</p>';
				}catch(Exception $e){
					ECHO 'AN ERROR OCCURED<br/>';
					ECHO $e->getMessage();
					mysql_query('ROLLBACK');
					echo '<p class="error"><b>Expense transaction not processed due to an SQL ERROR below.</b><br/>'.mysql_error().'</p>';	
				}
			$_SESSION['formHash'][$_POST['formid']] = $formHash;
			 else: 
				echo '<p class="suxs">Transaction completed sucessfully.</p>';
			endif;
	 }
	}
endif;
?>