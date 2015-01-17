<?php
defined('_FEXEC') or ('Access denied');
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/db_obj.php');
include_once('../system/generic.inc.php');
$driver	= new driver;
if(isset($_GET['wat'])){
			$wat	=	trim(stripslashes(strip_tags($_GET['wat'])));
			switch($wat){
				case 'csh':
					echo curCash();
				break;
				case 'acn':
					echo newAccount();	
				break;
				case 'rsv':
					echo reserve();
				break;
			}								
		}else if(isset($_GET['form'])){
			/*
			if(curCash()<=minCash()){//Check if the current cash is less or to the minimum cash and the stop transactions
				die('<p class="error">Can not make any transactions because cash is lower than the minimum cash balance!<br/>Advised to top up balance.</p> Cash is: '.curCash().' and min Cash is: '.minCash());						
			}else if(curCash()<=0){ //Check if the current cash is less or 0 and stop the transactions
				die('<p class="error">Can not make any transactions due to low cash balance!<br/>Advised to top up balance.</p>');										
				}*/
					$form	=	trim(stripslashes(strip_tags($_GET['form'])));
					switch($form):	
					CASE 'reg': //Start the client registeration process
						$name	=	trim(strip_tags(stripslashes(ucwords($_POST['name']))));
						$acno	=	trim(strip_tags(stripslashes($_POST['accno'])));
						$age	=	trim(strip_tags(stripslashes($_POST['age'])));
						$sex	=	trim(strip_tags(stripslashes($_POST['sex'])));
						$idno	=	trim(strip_tags(stripslashes($_POST['idno'])));
						$phno	=	trim(strip_tags(stripslashes($_POST['phno'])));
						$addr	=	mysql_real_escape_string(trim(strip_tags(stripslashes($_POST['addr']))));
						$pin	=	date('His').'-'.$acno;
						$pass	=	hash('haval256,3',$pin);
						$date	=	date('Y-m-d');
							$chkacn	= "SELECT name FROM clients WHERE acnumber='$acno'";
						if($query	= $driver->perform_request($chkacn)): //Check wether the client to be registered is already registered.
							if(mysql_num_rows($query)>0):
								$client = $driver->load_data($query);
								echo '<p class="error">The client <b>'.$client['name'].' is already registered with that account number('.$acno.')<br/> Close the window and try again!</p>';
							else:
								$reg="INSERT INTO clients VALUES('','$date','$name','$pass','$acno','$age','$sex','$idno','$phno','$addr')";
								$result	= $driver->perform_request($reg)?'<p class="suxs"> '.$name.' registered successfully.<br/>PIN NUMBER =>'.$pin.'</p>':'SQL ERROR: '.mysql_error();
							echo $result;
							endif;
						else:
							echo '<p class="error">SQL ERROR While registering client <br/>'.mysql_error().'</p>';
						endif;
					break; //Stop the client registeration process
					CASE 'ref':
						$refaccount	=	trim(strip_tags(stripslashes($_POST['refaccount'])));
						$refamount	=	trim(strip_tags(stripslashes($_POST['refamount'])));
						$chkacnt	=	"SELECT * FROM clients WHERE acnumber='$refaccount'";
						if($account = $driver->perform_request($chkacnt)){
							$nmrows	= mysql_num_rows($account);
							if($nmrows>0){
									$acdata		=	$driver->load_data($account,MYSQL_ASSOC);										
									$date		=	date('Y-m-d');
									
									$refamount	=	$refamount;
									$ntr		=	'REFUND';
									$rtype		=	1;
									$name		=	$acdata['name'];
									usleep(200);
									$curcsh		=	curCash(); //The current cash balance 
									$newcsh		=	curCash()+$refamount;
									$id			=	date('YmdHis'); //Computed transaction id
									$acountant			=	trim(strip_tags(stripslashes(mysql_real_escape_string($_SESSION['name']))));//The system user processing the transaction
									//SQL string for addig the transaction to the client_transactions table
									$pst_to_cl_trn	=	"INSERT INTO client_transactions VALUES('$id','$date','','$refaccount','$ntr','$name','','','','','$refamount','','','$refamount')";
									//SQL string for adding the transaction to the transactions table
									$pst_to_trn		=	"INSERT INTO transactions VALUES('$id','$date','','','$refaccount','$name','$ntr','','$rtype','','','','$refamount','$newcsh','$acountant')";
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
									echo ($driver->perform_request($pst_to_cl_trn) && $driver->perform_request($pst_to_trn))?'<p><span class="suxs">Refund from '.$name.' collected successfully':'<p><span class="error">SQL error: '.mysql_error().'</span></p>';
										$_SESSION['formHash'][$_POST['formid']] = $formHash;
								 else: 
									echo '<p class="suxs">Transaction completed sucessfully </p>';
								endif;
							}else{
								echo '<p><span class="error">Account '.$refaccount.' doesn\'t exist!</span></p>';
								}
						}											
					break;
					CASE 'exp':
						//Start processing the expense transaction
						$exptyp		=	trim(strip_tags(stripslashes($_POST['exptype']))); //Sanitize the expense type value 
						$expprtrs	=	trim(strip_tags(stripslashes($_POST['expparticulars']))); //Sanitize the expense particulars value
						$expamt		=	trim(strip_tags(stripslashes($_POST['expamount']))); //Sanitize the expense amount value	
						if($expamt<=0): //Check if the expense amount entered is less or equal to zero(0)
							echo '<span class="error">Error: Expense amoun can not be '.$expamt.' amount</span>'; //Print an error if expense amount is less or equal to zero(0)
							else: 
							usleep(200); //Let the script execution pause(sleep) for a few milliseconds
							$curcsh	=	curCash(); //Check for the current cash balance
							if($expamt>$curcsh){
								echo '<span class="error">Error: '.$expamt.'.00/= expense amount entered exceeds '.$curcsh.'.00/= current cash</span>';
							}else{
								usleep(200); //Let the script execution pause(sleep) for a few milliseconds
								$newcsh		=	curCash()-$expamt; //Compute the current cash
								$id			=	date('YmdHis'); //Computed transaction id
								$date		=	date("Y-m-d"); //Compute the current date
								$acountant			=	trim(strip_tags(stripslashes(mysql_real_escape_string($_SESSION['name']))));//The system user processing the transaction
								$exp_to_prs	=	"INSERT INTO transactions VALUES('$id','$date','','','$exptyp','$expprtrs','EXPENSE','','','','','','$expamt','$newcsh','$acountant')";
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
										echo ($driver->perform_request($exp_to_prs))?'<p class="suxs">EXPENSE SUBMITED SUCCESSFULLY</p>':'<p class="error"><b>Expense transaction not processed due to an SQL ERROR below.</b><br/>'.mysql_error().'</p>';
										$_SESSION['formHash'][$_POST['formid']] = $formHash;
										 else: 
											echo '<p class="suxs">Transaction completed sucessfully </p>';
										endif;
								 }
							}
						endif;
					break;
		CASE 'refunding': //Start giving refund transaction
			$id		=trim(stripslashes(strip_tags($_POST['refid']))); //Get and sanitize the transaction id
			$account=trim(stripslashes(strip_tags($_POST['account']))); //Get and sanitize the account number
			$amount	=trim(stripslashes(strip_tags($_POST['amount']))); //Get and sanitize the amount
			$name	=trim(stripslashes(strip_tags($_POST['clientname'])));//Get and sanitize the client's name
			$totrefund=trim(stripslashes(strip_tags($_POST['totrefund'])));//Get and sanitize the total amount for refund
			$usr		= trim(stripslashes(strip_tags($_SESSION['name']))); //Get and sanitize the account
				if(empty($id) or empty($account) or empty($amount) or empty($name)){
					die('<p class="error">Please make sure the required fields are field!<br/><b>Hint: </b> Close this window and try again.</p>');
				}else if(empty($amount) or !is_numeric($amount)){
					die('<p class="error">Enter refund amount or make sure the entered amount is digits only! <br/><b>Hint: </b> Close this window and try again.</p>');
				}else{
					if($id == 'totrefund' and $amount>$totrefund):
						die('<p class="error">Make sure the amount does not exceed the client total refunds!<br/><b>Hint: </b> Close this window and try again.</p>');									
					elseif($id == 'totrefund' and $amount<$totrefund):
						die('<p class="error">Enter full amount or select a single refund!<br/><b>Hint: </b> Close this window and try again.</p>');
					elseif($id	==	'totrefund' and $amount==$totrefund):
						$cash		=	(curCash()-$amount);
					elseif($id != 'totrefund'):
						$cash		=	(curCash()-$amount);
						$sql	=	"SELECT refund FROM client_transactions WHERE id='$id'";
						if($result	=	 $driver->perform_request($sql)):
							$row	=	$driver->load_data($result,MYSQL_ASSOC);
							$refund_on_id	=	$row['refund'];
							$bal_refund	=	($refund_on_id-$amount);
						else:
									die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Refund error[1], '.mysql_error().'</p>');										
						endif;
					endif;
					if($amount>=curCash()){
						die('<p class="error">First top up cash balance to be able to give such '.$amount.' amount! <br/><b>Hint: </b> Close this window and try again.</p>');
					}else if(curCash()<=minCash()){//Check if the current cash is less or to the minimum cash and the stop transactions
				die('<p class="error">Can not make any transactions because cash is lower than the minimum cash balance!<br/>Advised to top up balance.</p> Cash is: '.curCash().' and min Cash is: '.minCash());						
				}else if(curCash()<=0){ //Check if the current cash is less or 0 and stop the transactions
				die('<p class="error">Can not make any transactions due to low cash balance!<br/>Advised to top up balance.</p>');										
				}else{
							$tid		=	date('YmdHis'); //Computed transaction id
							$date		=	date('Y-m-d'); //Current date
							$sql6		=	($id=='totrefund')?"UPDATE client_transactions SET refund='0' WHERE acnumber='$account'":"UPDATE client_transactions SET refund='$bal_refund' WHERE id='$id'";
							$sql8		=	"INSERT INTO client_transactions VALUES('$tid','$date','','$account','refund','$name','','','','','','','','$amount')";
							$sql7		=	"INSERT INTO transactions VALUES('$tid','$date','','','$account','$name','refund','','2','','','','$amount','$cash','$usr')";
							
							if($driver->perform_request($sql6) and $driver->perform_request($sql7) and $driver->perform_request($sql8)){
								echo '<p class="suxs">Refund transaction completed successfully!</p>';
							}else if(mysql_error() and strpos("Duplicate entry",mysql_error())){
									echo '<p class="suxs">Refund transaction completed successfully!</p>';
								}else{
									die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Refund error[2], '.mysql_error().'</p>');														}								
								}
							
					}							
		break;//End (stop) giving refund transaction
		endswitch;		
		}else if(isset($_GET['proc'])){
			$proc	=	trim(stripslashes(strip_tags($_GET['proc'])));
			switch($proc):
				case 'col_rec':
					recoverable($_GET['id']);
				break;
				case 'col_ref':
					refundable($_GET['id']);
				break;
			endswitch;								
		}else{//Stop execution and trigger error because the user has not submitted any form
				die('<p class="error">You did not process any transaction.<br/>Please try again!</p>');
			}
?>