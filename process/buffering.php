<?php
defined('_FEXEC') or ('Access denied');
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/db_obj.php');
include_once('../system/generic.inc.php');
$driver	= new driver;
	$typ	=	$driver->clean($_POST['buftype'],1,1);
	$amt	=	$driver->clean($_POST['bufamount'],1,1);
	$prtrs	=	$driver->clean($_POST['bufparticulars'],1,1);
	$buftyp	=	($typ<=4)?1:2;
	if($amt<=0){                                                          
		echo '<span class="error">Error: Buffer can not be '.$amt.' amount</span>';
	}else if($buftyp==1){
		usleep(200); //Stop excution for around 200 milliseconds inorded to retrieve the current cash balance
		$curcsh	=  curCash(); //The current cash balance 
	if($amt>$curcsh){
			echo '<span class="error">Error: '.$amt.'.00/= buffer amount entered exceeds '.$curcsh.'.00/= current cash</span>';
	}else{
		$date	=	date('Y-m-d');
		$accnum	=	'RESERVE'; //The account number of the transaction
		$prtrs	=	$prtrs; //Particulars
		$ntr	=	'CASH'; //All buffers' nature is CASH
		$buftyp	=	$buftyp; // The buffer type either 1 or 2
		$amt	=	$amt;	//The buffer amount
		$nwreserve = 0; // The new reserve amount
		$newcsh	=	(curCash()-$amt); //Calculate the new cash
		$id		=	date('YmdHis'); //Computed transaction id
		$acountant	=	$driver->clean($_SESSION['name'],1,1);//The system user processing the transaction
		$buf_to_prs = "INSERT INTO transactions	VALUES('$id','$date','','','$accnum','$prtrs','$ntr','$buftyp','','','','','$amt','$newcsh','$acountant')";
		if($typ == 4 OR $typ == 8):
			$nwreserve = ($typ == 8)? -$amt:$amt;
			$reserve = "INSERT INTO reserve VALUES('','$date','$typ','$nwreserve')";			
		endif;
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
			mysql_query("BEGIN");
			$trs = $driver->perform_request($buf_to_prs);
			$reserve = $driver->perform_request($reserve);
			if(!$trs or !$reserve){
				mysql_query("ROLLBACK");
				echo '<p class="error">An SQL ERROR: '.mysql_error().'</p>';																$_SESSION['formHash'][$_POST['formid']] = $formHash;
			}else{
				mysql_query('COMMIT');
				$_SESSION['formHash'][$_POST['formid']] = $formHash;
				echo '<p class="suxs">BUFFER SUBMITED SUCCESSFULLY</p>';																
				}
		 else: 
			echo '<p class="suxs">Transaction completed sucessfully </p>';
		endif;
		}
		}
	}else{
		usleep(200); //Stop excution for around 200 milliseconds inorded to retrieve the current cash balance
		$curcsh	=  curCash(); //The current cash balance 										
		$date	=	date('Y-m-d');
		$accnum	=	'RESERVE'; //The account number of the transaction
		$prtrs	=	$prtrs; //Particulars
		$ntr	=	'CASH'; //All buffers' nature is CASH
		$buftyp	=	$buftyp; // The buffer type either 1 or 2
		$amt	=	$amt;	//The buffer amount
		$newcsh	=	(curCash()+$amt); //Calculate the new cash
		$id		=	date('YmdHis'); //Computed transaction id
		$acountant	=	$driver->clean($_SESSION['name'],1,1);//The system user processing the transaction
		$buf_to_prs = "INSERT INTO transactions	VALUES('$id','$date','','','$accnum','$prtrs','$ntr','$buftyp','','','','','$amt','$newcsh','$acountant')";
		if($typ == 4 OR $typ == 8):
			$nwreserve = ($typ == 8)? -$amt:$amt;
			$reserve = "INSERT INTO reserve VALUES('','$date','$typ','$nwreserve')";			
		endif;
		$allvals	= 	'';
			foreach($_POST as $k=>$val):
				$allvals .=$val;
			endforeach;
			
			$formHash	= md5($allvals);
			$allowAction	=	true;

			if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
				$allowAction = false;
			}
		 if($allowAction==true):
			mysql_query("BEGIN");
			$trs = $driver->perform_request($buf_to_prs);
			$reserve = $driver->perform_request($reserve);
			if(!$trs or !$reserve){
				mysql_query("ROLLBACK");
				echo '<p class="error">An SQL ERROR: '.mysql_error().'</p>';
				$_SESSION['formHash'][$_POST['formid']] = $formHash;
			}else{
				mysql_query('COMMIT');
				$_SESSION['formHash'][$_POST['formid']] = $formHash;
				echo '<p class="suxs">BUFFER SUBMITED SUCCESSFULLY</p>';																
				}
		 else: 
			echo '<p class="suxs">Transaction completed sucessfully </p>';
		endif;
}
?>