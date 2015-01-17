<?php
	defined('_FEXEC') or ('Access denied');
	session_start();
	ini_set('date.timezone','Africa/Nairobi');
	include_once('../system/generic.inc.php');
	$driver	= new driver;
    //Create and initialize some variables
	$principal 	= 0; 
	$interest 	= 0;
	$due		= 0;
	$accnum	=	$driver->clean($_POST['accountnumber'],1,1);//Create and clean up the posted account number
	$id		=	isSet($_POST['id'])?$driver->clean($_POST['id'],1,1):'';	//Sanitize the id
	$amount	=	$driver->clean($_POST['amount'],1,1);//Sanitize the amount
	$usr	=	$driver->clean($_POST['accountant'],1,1); //Sanitize the user
	$name	=	$driver->clean($_POST['clientname'],1,1);//Sanitize the client name
	$totbal	=	$driver->clean($_POST['totbal'],1,1);//Sanitize total balance
	if(empty($id) or $id=='' or $id==null or $id==0){	//Check if the cleaned id is empty and return or print an error message
        die('<p class="error"><b>Choose a particular transaction from the client\'s disbursements!</b></p>');
		}

if(empty($amount) or $amount=='' or $amount==0){//Check if the cleaned amount is empty and return or print an error message
			die('<p class="error">Enter a recovery amount!</p>');
		}
if($amount>0 and !$driver->digits($amount)){ //Check if the entered and cleaned amount is a number or decimal otherwise print error MSG and stop excution
						die('<p class="error">Enter recovery amount in digits!</p>');
		}
				$getdata	=  "SELECT principalbal,interestbal,balance FROM client_transactions WHERE id='$id'";
	if($result 	= $driver->perform_request($getdata)):
								$row 	= $driver->load_data($result);
								$principal	=	$row['principalbal'];
								$interest	=	$row['interestbal'];
								$due		=	$row['balance'];
							else:
									/*Will run the code to record error logs in the database here.
									before displaying error to the user
									*/
	die('<p class="error">Error: R1 encountered</p><p class="error">Administrator will be notified with the error!</p>');
							endif;								
							if($amount==$due){
								$change	=	0;
								$ref	=	0;
								$prp 	= $principal;
								$int 	= $interest;
								}else if($amount>$due){
									$change = 0;
									$ref 	= $amount-$due;
									$prp	= $principal;
									$int 	= $interest;									
								}else{
									if(($amount-$principal)>0){
										$change 	= $due-$amount;
										$ref		= 0;
										$prp 		= $principal;
										$int 		= ($amount-$principal);
									}else if(($amount-$principal)<0){
										$change 	= $due-$amount;
										$ref 		= 0;
										$prp 		= $amount;
										$int 		= 0;
										}else if(($amount-$principal)==0){
											$prp 	= $amount;
											$int 	= 0;
											$ref 	= 0;
											$change	= $due-$amount;
											}		
									}
										$prp_bal 	=	($principal - $prp);
										$int_bal 	=	($interest	- $int);
										$change_bal	=	($due	- $change);						
										usleep(200); //Let the script execution pause(sleep) for a few milliseconds
										$tid		=	date('YmdHis'); //Computed transaction id
										$date		=	date("Y-m-d"); //Compute the current date
										$cash		=	(curCash()+$amount);	// The new cash balance
$allvals	= 	'';
foreach($_POST as $k=>$val):
    $allvals .=$val;
endforeach;
$formHash	= md5($allvals);
$allowAction	=	true;
if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
    $allowAction = false;
}
$sql6 = ($id=='totrecover')?"UPDATE client_transactions SET due='0',balance='0' WHERE balance>0 AND $cnumber='$accnum'":"UPDATE client_transactions SET principalbal='$prp_bal',interestbal='$int_bal',balance='$change',due='$change' WHERE id='$id'";
/*	if($id=='totrecover'){
$sql6 = "UPDATE client_transactions SET due='0',balance='0' WHERE balance>0 AND acnumber='$accnum'";
										}else{
$sql6 = "UPDATE client_transactions SET principalbal='$prp_bal',interestbal='$int_bal',balance='$change',due='$change' WHERE id='$id'";
										}*/
//"UPDATE client_transactions SET principalbal='$prp_bal',interestbal='$int_bal',balance='$change' WHERE id='$id'";
$sql7	=	"INSERT INTO transactions VALUES('$tid','$date','','','$accnum','$name','recovery','','','$prp','$int','$ref','$amount','$cash','$usr')";
$sql8	=	"INSERT INTO client_transactions VALUES('$tid','$date','','$accnum','recovery','','$name','$prp','$int','$prp_bal','$int_bal','$ref','$change','$change','$amount')";

if($allowAction==true){
$driver->perform_request($sql6) or // put some code to send error in mail and sms
    die('<p class="error">System error occured:'. mysql_error().' Administrator will be notified</p> ');
$driver->perform_request($sql7) or // put some code to send error in mail and sms
    die('<p class="error">System error occured:'. mysql_error().' Administrator will be notified</p> ');
 $driver->perform_request($sql8) or // put some code to send error in mail and sms
  die('<p class="error">System error occured:'. mysql_error().' Administrator will be notified</p> ');
$_SESSION['formHash'][$_POST['formid']] = $formHash;
 echo '<p class="success">Recovery transaction completed successfully!</p>';


 /*                                            if(){
													if(){
														if($driver->perform_request($sql8)){
echo '<p class="suxs">Recovery transaction completed successfully!</p>';
$_SESSION['formHash'][$_POST['formid']] = $formHash;
															}
													}
										}else{
											if(mysql_error() && strpos("Duplicate entry",mysql_error())){
continue;
echo '<p class="success">Recovery transaction completed successfully!</p>';
												$_SESSION['formHash'][$_POST['formid']] = $formHash;												
											}else{
											echo '<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3>';
											echo '<p class="error">Recovery error[3],'.mysql_error().'</p>';
											}
										}*/
}else{
    echo '<p class="success">Recovery transaction completed successfully!</p>';
}
?>