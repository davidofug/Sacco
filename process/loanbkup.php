<?php
	session_start();
	ini_set('date.timezone','Africa/Nairobi');
	include_once('../system/generic.inc.php');
	$driver	= new driver;
	$date=date('Y-m-d'); //Current date
	$id=date('YmdHis'); //Computed transaction id
	$dateAndTime=date('Y-m-d H:i:s'); //The php date and time for MySql
	$repay=mktime(0,0,0,date("m"),date("d")+loanExpire(),date("Y")); //Make date when client is supposed to pay back
    $dues= 0;
    $pay_back_date = null;
$account=$driver->clean($_POST['accountnumber'],1,1); //Sanitize account number
$acountant=$driver->clean($_POST['accountant'],1,1); //Sanitize client name
$principal=$driver->clean($_POST['amount'],1,1);//Sanitize disbursement amount
$name=$driver->clean($_POST['name'],1,1);//Sanitize the client's name
$interest=((intRate()/100)*$principal);//The interest amount
$curcash=(curCash() - $principal); //The current cash after removing the principal from the previsous balance
$due=($principal+$interest); //Amount to pay after the loan has been issued

if(empty($principal) || ($principal<=0)):
die('<p class="error">Error: Disbursement amount can not be zero or( '.$principal.') amount.</p>'); //Print an error if disbursement amount is less or equal to zero(0)
endif;
if(empty($account)):
die('<p class="error">Error: Please make sure the target account number is not empty!.</p>'); //Print an error if client account not specified
endif;
$sql="SELECT * FROM client_transactions WHERE nature='disb' AND balance>'0' AND disbtype='1' AND acnumber='$account'";
$sql2="SELECT pay_date FROM client_transactions WHERE nature='disb' AND balance>'0' AND disbtype='1' AND acnumber='$account' ORDER BY pay_date DESC LIMIT 1";
	if($data = $driver->perform_request($sql)){//Retrieve client dues
			while($trns = $driver->load_data($data,MYSQL_ASSOC)):
				$dues+=$trns['balance'];
                $pay_back_date=($trns['pay_date']>$today)?$trns['pay_date']:date('Y-m-d',$repay);//Find the right date for the loan to be cleared
			endwhile;
    }else{
        //Client's loan history can not be retrieved
        /*Will run the code to record error logs in the database here.
        before displaying error to the user
        */
die('<p class="error">Error: L1,C1,AR1  encountered</p><p class="error">Administrator will be notified with the error!</p><p>'.MYSQL_error().'</p>');
    }
if($data = $driver->perform_request($sql2)){//Retrieve client dues
$trns = $driver->load_data($data,MYSQL_ASSOC);
//      ECHO "pAY DATE: ". $pay_back_date = ($trns['pay_date']>$today)?$trns['pay_date']:date('Y-m-d',$repay);//Find the right date for the loan to be cleared
}else{
    //Client's loan history can not be retrieved
    /*Will run the code to record error logs in the database here.
    before displaying error to the user
    */
    die('<p class="error">Error: L1,C1,AR1  encountered</p><p class="error">Administrator will be notified with the error!</p><p>'.MYSQL_error().'</p>');
}

 if($dues>=maxLoan())://4
			die('<p class="error">This client has reached the maximum disbursement amount of UGX '.maxLoan().'/=<br/> System does not allow him/her more loans until petending loans are recovered!</p>');
endif;
if($principal>=curCash())://5
				die('<p class="error">Can not loan amounts above '.curCash().'\= current cash, consult admin or top up balance! </p>');
endif;
if($principal>maxLoan()):
				die('<p class="error">Can not loan amounts above '.maxLoan().'\= !<br/> Which is the maximum amount you can give out as a loan.</p>');
endif;
if(curCash()<=minCash()):
die('<p class="error">You need to top up the current cash from '.minCash().'\=, to complete the loan! </p>');
endif;
if(($dues+$principal)>maxLoan()):
die('<p class="error">The system can not give a loan since the client\'s balance of  (UGX'.$dues .'/=) + today\'s amount (UGX'.$principal.'/=) = UGX'.($dues+$principal).'/= will surpass the maximum loan amount of UGX'.maxLoan().'/=</p>');
endif;
if($principal>=curCash()):
die('<p class="error">Can not loan amounts above '.curCash().'\= current cash, consult admin or top up balance! </p>');
endif;
if($principal>maxLoan()):
die('<p class="error">Can not loan amounts above '.maxLoan().'\= !<br/> Which is the maximum amount you can give out as a loan.</p>');
endif;
$clients="INSERT INTO client_transactions VALUES('$id','$date','$pay_back_date','$account','disb','1','$name','$principal','$interest','$principal','$interest','','$dues','$dues','$principal')"; //Query to tie the transaction onto a specific client
$transaction="INSERT INTO transactions VALUES('$id','$date','$dateAndTime','$pay_back_date','$account','$name','disb','','','$principal','$interest','$dues','','$curcash','$acountant')";//Query to record/save the transaction in the general transactions
$allvals= '';
foreach($_POST as $k=>$val):
$allvals .=$val;
endforeach;
$formHash= md5($allvals);
$allowAction=true;
if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
$allowAction = false;
}
if($allowAction==true){
	if($driver->perform_request($clients) AND $driver->perform_request($transaction))://Queries processed successfully?
	echo '<p class="suxs">Loan transaction completed successfully!</p>';
	$_SESSION['formHash'][$_POST['formid']] = $formHash; //Save form data as a hash in a session
	else:
	echo '<p class="error">Transaction not completed <br/>'.mysql_error().'</p>';
	endif;//End the above query execution
}else{
	echo '<p class="suxs">Loan transaction completed sucessfully.</p>';
}
?>