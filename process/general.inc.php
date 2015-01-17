<?php
function newAccount(){ //FUNCTION: newAccount USAGE: To generate the new account number for newly registered client
				global $driver;
				$sql = "SELECT acnumber FROM clients ORDER BY id DESC LIMIT 1"; //Retrieve the latest account number from clients table
				if($results	= $driver->perform_request($sql)):
					$row		=	$driver->load_data($results,MYSQL_ASSOC);
					$account	=	$row['acnumber'];
					$newac		=	$account+1;
						switch($newac):
							CASE $newac < 10:
							$newac	=	'000'.$newac;
							break;
							CASE $newac>=10 && $newac<100:
							$newac	=	'00'.$newac;
							break;
							CASE $newac>=100 && $newac<1000:
							$newac	=	'0'.$newac;
							break;
							default:
							$newac	=	$newac+1;
						endswitch;
					else: 
						die('<p class="error">Failed to generate New ACC/No: <br/>'.mysql_error().'</p>');
					endif;
			return $newac; //The generated account number
}
function intRate(){ //FUNCTION: intRate USAGE: To return the set interest rate
		global $driver;
		$sql =	"SELECT interestrate FROM settings"; //Retrieve the interest rate
		if($results	= $driver->perform_request($sql)):
				$row	= $driver->load_data($results,MYSQL_ASSOC); 
				$interest = ($row['interestrate']>0)?($row['interestrate']):20;
		else:
			die('<p class="error">Interest rate Error: '.mysql_error().'</p>');
		endif;
	return $interest; //The interest rate
}
function backupDir(){
	global $driver;
	$sql =	"SELECT backupdir FROM settings"; //Retrieve the backup directory name
	if($bckdir	=	$driver->perform_request($sql)):
		$row	=	$driver->load_data($bckdir);
		$dir	=	(!empty($row['backupdir']))?($row['backupdir']):'finData';//The retrieved backup directory name
	else:
			die('<p class="error">Failed to retrieve backup directory: '.mysql_error().'</p>');
	endif;
	return $dir; //The backup directory name
}
function curCash(){ //FUNCTION: curCash USAGE: To return the current or system initial cash
	global $driver;
	$sql_settings		=	"SELECT systeminitcash FROM settings"; //Retrieve system initial cash
	$sql_transactions	=	"SELECT c_balance FROM transactions ORDER BY id DESC LIMIT 1";
	if($cash	=	 $driver->perform_request($sql_transactions)):
		if($driver->numRows($cash)>0):
			$row = $driver->load_data($cash,MYSQL_ASSOC);
			$cashamount	=	($row['c_balance']>0)?($row['c_balance']):0;//Current cash balance
		else:
			if($cash 	=	$driver->perform_request($sql_settings)):
				$row	= 	$driver->load_data($cash,MYSQL_ASSOC);
				$cashamount = ($row['systeminitcash']>0)?($row['systeminitcash']):0;//System Initial Cash
			else:
				die('<p class="error">ERROR Retrieving Initial Cash <br/>'.mysql_error().'</p>');
			endif;
		endif;
	else:
		die('<p class="error">ERROR Retrieving Cash balance<br/>'.mysql_error().'</p>');
	endif;
	return $cashamount;
}
function maxLoan(){	//FUNCTION: maxLoan USAGE: To return the amount to loan
	global $driver;
	$sql	=	"SELECT maxloan FROM settings"; //Retrieve maximum loan amount to be given
	if($maxloan		=	$driver->perform_request($sql)):
		$row		=	$driver->load_data($maxloan);
		$maxloanamt	=	(($row['maxloan'])>0)?($row['maxloan']):500000;
	else:
		die('<p class="error">ERROR Retrieving Maximum Loan Amount.<br/>'.mysql_error().'</p>');
	endif;
	return $maxloanamt; //The maximum amount to loan
}
function loanExpire(){ //FUNCTION loanExpire USAGE: To return the number of days a client is allowed to return the disbursed 
	global $driver;
	$sql	=	"SELECT loanduration FROM settings"; //Retrieve the maximum days the loanee will be given before being flagged as a defaulter
	if($loanexpire	=	$driver->perform_request($sql)):
		$row	=	$driver->load_data($loanexpire);
		$maxdays	=	($row['loanduration']>0)?($row['loanduration']):30; //Retrieved loan duration
	else:
		die('<p class="error">ERROR Retrieving Loan duration.<br/>'.mysql_error().'</p>');
	endif;
	return $maxdays; //The maximum days before loan is pushed to arrear
}
function minCash(){ //FUNCTION minCash USAGE: For returning the system minimum balance
		global $driver;
		$sql		=	"SELECT leastcash FROM settings";
	if($leastcash	=	$driver->perform_request($sql)):
		$row		=	$driver->load_data($leastcash);
		$mincash	=	($row['leastcash']>0)?($row['leastcash']):500000;
	else:
		die('<p class="error">ERROR Retrieving minimum cash balance.<br/>'.mysql_error().'</p>');
	endif;
	return $mincash; //The minimum cash
}
function sysName(){ //FUNTION sysName USAGE: For returning the application name
	global $driver;
	$sql	=	"SELECT systemname FROM settings"; //Retrieve the system name
	if($appname	= $drive->perform_request($sql)):
		$row	= $driver->load_data($appname);
		$sysname	=	(!empty($row['systemname']))?($row['systemname']):" Microfinance ";//Retrieved system name
	else:
			die('<p class="error">ERROR Retrieving Application name.<br/>'.mysql_error().'</p>');
	endif;
	return $sysname; //The application name
}
function recoverable($client){ //FUNCTION unrecoveredLoans USAGE: To retrieve a specific clients loans which have not been recovered yet
global $driver;
	$client=trim(stripslashes(strip_tags($client))); // Sanitize the input
	$tot_prp = null;
	$tot_int = null;
	$tot_bal = null;
	$prpbal	 = null;
	$intbal	 = null;
	$sql	=	"SELECT * FROM client_transactions WHERE nature='disb' AND acnumber='$client' AND balance>0 ORDER BY id DESC";
	if($results	= $driver->perform_request($sql)):
		$num	=	mysql_num_rows($results);
			if($num>0):
			
				$unrecovered	 =	'<p>Acccount number <b>'.$client.'</b> has <b>'.$num.'</b> unrecovered loans</p>';
				$unrecovered	.= 	'<p>list below</p>';
				$unrecovered	.=	'<p>&nbsp;</p><table width="550" border="1"><tr><td><b>Select</b></td><td><b>Date Acquired</b><td><b>Principal</b></td>';
				$unrecovered	.=	'<td><b>Interest</b></td><td><b>Amount due</b></td></tr>';
					while($row		=	$driver->load_data($results,MYSQL_ASSOC)):
					extract($row);
					if($principalbal ==0):
						$principalbal = 'rec\'vrd';
					endif;
					if($interestbal ==0):
						$interestbal ='rec\'vrd';
					endif;
					$unrecovered .= '<tr><td><input type="radio" name="id" value="'.$id.'" id="loanid" class="checkbox" /></td><td>'.$date.'</td><td>'.$principalbal.'</td><td>'.$interestbal.'</td><td>'.$balance.'</td></tr>';
					$tot_prp	+=$principalbal;
					$tot_int	+=$interestbal;
					$tot_bal	+=$balance;
					$name		= $name;
					endwhile;
				$formid		=	microtime(true)*10000;
				$unrecovered	.='<tr><th colspan="2">GRAND TOTALS</th><th>'.$tot_prp.'</th><th>'.$tot_int.'</th><th>'.$tot_bal.'</th></tr>';
				$unrecovered	.='<input type="hidden" name="formid" id="formid" value="'.$formid.'"/><input type="hidden" value="'.$tot_bal.'" name="totbal" /><input type="hidden" name="clientname" value="'.$name.'" />
				<input type="hidden" name="account" value="'.$client.'" />';
				$unrecovered	.='<tr><th colspan="2">TOTAL AMOUNT DEMANDED</th><th colspan="2">'.$tot_bal.'</th><td>&nbsp;</td></tr>';
				$unrecovered	.='<tr><th colspan="4">Enter amount: </th><td><input type="text" name="amount" id="amount" /></td></tr>';
				$unrecovered	.='<tr><td colspan="5" align="right"><input type="submit" value="Recover" name="Recover" /></td></tr></table></div>';
				echo '<div id="recover_s_container"><form id="recover" method="post" action="process/recover.php">'.$unrecovered.'</form></div>';
			else:
				die('<p class="error">Can not collect recoveries from client!</p>');
			endif;
	else:
		die('<p class="error">SQL Error occured: <br/>'.mysql_error().'</p>');
	endif;

}
function refundable($client){
	global $driver;
	$tot_ref = null;
	$client=trim(stripslashes(strip_tags($client))); // Sanitize the input
			$sql	=	"SELECT * FROM client_transactions WHERE acnumber='$client' and refund>'0' ORDER BY id DESC";
				if($results	= $driver->perform_request($sql)){
					$num	=	mysql_num_rows($results);
					if($num>0){
					$refundable	= 	'<h3>Client\'s refunds\' list below</p></h3>';
					$refundable	.=	'<table width="600" border="1"><tr><th>Select one</th><th>Date</th><td>Amount for refund</th></tr>';
					while($row		=	$driver->load_data($results,MYSQL_ASSOC))
					{extract($row);
						if($refund>0):
						$refundable .= '<tr><td><input type="radio" value="'.$id.'" name="refid" class="radiobutton" /></td><td>'.$date.'</td><td>'.$refund.'</td></tr>';
						endif;
						$tot_ref	+=	$refund;
					}
					$formid		=	microtime(true)*10000;
					$refundable	.='<tr><th><input type="radio" value="totrefund" name="refid" class="radiobutton" /></th><th>GRAND TOTAL</th><th>'.$tot_ref.'</th></tr>';
					$refundable .='<input type="hidden" name="formid" id="formid" value="'.$formid.'"/><input type="hidden" value="'.$tot_ref.'" name="totrefund" /><input type="hidden" name="clientname" value="'.$name.'" /><input type="hidden" name="account" value="'.$client.'" />';
					$refundable	.='<tr><th>Enter amount: </th><td colspan="2"><input type="text" name="amount" id="amount" /></td></tr>';
					$refundable	.='<tr><td colspan="3" align="right"><input type="submit" value="Refund" name="Refund" /></form></tr></table>';
					
					echo '<div id="refunding_s_container"><form id="refunding" method="post" action="process/process.php?form=refunding">'.$refundable.'</form></div>';
					}else{
						die('<p class="error">You can not give this client refunds.</p>');
						}
					}else{
						die('<p class="error">SQL Error occured: '.mysql_error().'</p>');
						}
	
}
function disbursed($client,$total=0){
$distotal =0;
global $driver;
if($total>0):
return $distotal;
endif;
}
function refunded($client,$total=0){
$reftotal =0;
global $driver;
if($total>0):
return $reftotal;
endif;
}
function recovered($client,$total=0){
$rectotal =0;
global $driver;
if($total>0):
return $rectotal;
endif;
}
?>