<?php	
	session_start();
	ini_set('date.timezone','Africa/Nairobi');
	include_once('../system/generic.inc.php');
	$driver	= new driver;	
	$account		=	$driver->clean($_POST['accountnumber'],1,1); //Sanitize account number	
	$date			=	date('Y-m-d'); //Current date
	$id				=	date('YmdHis'); //Computed transaction id
	$dateAndTime	=	date('Y-m-d H:i:s'); //The php date and time for MySql
	$period			=	$driver->clean($_POST['period'],1,1);
	$in				=	$driver->clean($_POST['in'],1,1);//echo loanExpire($period,$in);
	$repay			=	mktime(0,0,0,date("m"),date("d")+loanExpire($period,$in),date("Y")); //Make date when client is supposed to pay back
	$pay_back_date	=	date('Y-m-d',$repay); //Initialise the values from above to date object
	$particular		=	$driver->clean($_POST['name'],1,1); //Sanitize client name								
	$principal		=	$driver->clean($_POST['disbamount'],1,1);//Sanitize disbursement amount
	$curcash		=	(curCash()- $principal); //The current cash after removing the principal from the previsous balance
	$acountant		=	$driver->clean(mysql_real_escape_string($_SESSION['name']),1,1);//The system user processing the transaction						
	$formint		= 	$driver->clean($_POST['interestrate'],1,1);
	$term			=	isSet($_POST['terms'])?$driver->clean($_POST['terms'],1,1):'30';
		if($formint>0){
				if($period>0){
					if($in=='M'){
						$interestAmt 		= 	($period*(($formint/100)*$principal));
					}else if($in=='Y'){
						$interestAmt 		= 	(($period*12)*(($formint/100)*$principal));
						}
				}else{
					$interestAmt 		=	($formint/100)*$principal;
				}
			}else{
				 $interestAmt	= 	(intRate()/100)*$principal;	//The interest amount
			}
		$nwarrears			=	($principal+$interestAmt); //Amount to pay after the loan has been issued

		if(empty($principal) || ($principal<=0)):
			die('<p class="error">Error: Disbursement amount can not be '.$principal.' amount!</p>'); //Print an error if disbursement amount is less or equal to zero(0)						
		endif;
		if(empty($account)):
			die('<p class="error">Error: The account number to be given loan was not specified!</p>'); //Print an error if disbursement amount is less or equal to zero(0)						
		endif;
		if(!empty($principal) && !empty($account)){//Disbursement and account fields must be set
			$sql	=	"SELECT * FROM client_transactions WHERE acnumber='$account' AND balance>'0'";
			if($arrears = $driver->perform_request($sql)){//2
				if($driver->numRows($arrears)>0){//3
						$arrearamt	= 0;
					while($amts = $driver->load_data($arrears,MYSQL_ASSOC)):
						$arrearamt	+=	$amts['balance'];
					endwhile;
					/*if($arrearamt>=maxLoan())://4
						die('<p class="error">This client has reached the maximum disbursement amount of UGX '.maxLoan().'/=<br/> System does not allow him/her more loans until petending loans are recovered!</p>');
					 else:*/
						if($principal>=curCash())://5
							die('<p class="error">Can not loan amounts above '.curCash().'\= current cash, consult admin or top up balance! </p>');																									
						/*elseif($principal>maxLoan()):
							die('<p class="error">Can not loan amounts above '.maxLoan().'\= !<br/> Which is the maximum amount you can give out as a loan.</p>');														
						*/
						elseif(minCash()>=curCash()):
							die('<p class="error">You need to top up the current cash from '.minCash().'\=, to complete the loan! </p>');	
						elseif(curCash()<=0):
							die('<p class="error">You need to top up the current cash from '.minCash().'\=, to complete the loan! </p>');	
						/*elseif(($arrearamt+$principal)>maxLoan()):
							die('<p class="error">The system can not give a loan since the client\'s balance of  (UGX'.$arrearamt .'/=) + today\'s amount (UGX'.$principal.'/=) = UGX'.($arrearamt+$principal).'/= will surpass the maximum loan amount of UGX'.maxLoan().'/=</p>');
						*/else:
							$clients			=	"INSERT INTO client_transactions VALUES('$id','$date','$pay_back_date','$account','disb','$particular','$principal','$interestAmt','$principal','$interestAmt','','$nwarrears','$nwarrears','$principal')"; //Query to tie the transaction onto a specific client
							$transaction		=	"INSERT INTO transactions VALUES('$id','$date','$dateAndTime','$pay_back_date','$account','$particular','disb','','','$principal','$interestAmt','$nwarrears','','$curcash','$acountant')";//Query to record/save the transaction in the general transactions
							if($driver->perform_request($clients) AND	$driver->perform_request($transaction))://6
								echo '<p class="suxs">Loan transaction completed successfully!</p>';
							else:
								if(mysql_error() && strpos("Duplicate entry",mysql_error())){
									echo '<p class="suxs">Loan transaction completed successfully!</p>';
								}else{
								echo '<p class="error">Transaction not completed <br/>'.mysql_error().'</p>';
								}
							endif;
						endif;
					/*endif; */
			}else{
						if($principal>=curCash()):
							die('<p class="error">Can not loan amounts above '.curCash().'\= current cash, consult admin or top up balance! </p>');																									
						/*elseif($principal>maxLoan()):
							die('<p class="error">Can not loan amounts above '.maxLoan().'\= !<br/> Which is the maximum amount you can give out as a loan.</p>');														
						*/elseif(minCash()>=curCash()):
							die('<p class="error">You need to top up the current cash from '.minCash().'\=, to complete the loan! </p>');	
						else:
							$clients			=	"INSERT INTO client_transactions VALUES('$id','$date','$pay_back_date','$account','disb','$particular','$principal','$interestAmt','$principal','$interestAmt','','$nwarrears','$nwarrears','$principal')"; //Query to tie the transaction onto a specific client
							$transaction		=	"INSERT INTO transactions VALUES('$id','$date','$dateAndTime','$pay_back_date','$account','$particular','disb','','','$principal','$interestAmt','$nwarrears','','$curcash','$acountant')";//Query to record/save the transaction in the general transactions
						$allvals	= 	'';
						foreach($_POST as $k=>$val):
							$allvals .=$val;
						endforeach;
						
						$formHash	= md5($allvals);
						$allowAction	=	true;
						
						if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
							$allowAction = false;
						}
						if($allowAction==true){
							if($driver->perform_request($clients) AND	$driver->perform_request($transaction))://6
								echo '<p class="suxs">Loan transaction completed successfully!</p>';
								$_SESSION['formHash'][$_POST['formid']] = $formHash;
							else:
								echo '<p class="error">Transaction not completed <br/>'.mysql_error().'</p>';
							endif;//End the above query execution
						}else{
							echo '<p class="suxs">Transaction completed sucessfully </p>';
						}
						endif;
					}
		}else{//Client's loan history can not be retrieved
		die('<p class="error">SQL Error: Retrieving client details failed. <br/>'.mysql_error().'<br/>Close this window and try again, if error persists contact administrator</p>');
			}
	}else{//If principal or account field is not set
		if(empty($principal))://If principal is not set
			die('<p class="error">Please enter amount</p>');
		elseif(empty($account))://If it's amount not entered
			die('<p class="error">Please select a client to be given the loan!</p>');
										endif;
										}//End of principal and account number checks
	?>