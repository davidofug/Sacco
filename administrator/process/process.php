<?php
			defined('_FEXEC') or ('Access denied');
			session_start();
			ini_set('date.timezone','Africa/Nairobi');
			include_once('../../system/db_obj.php');
			include_once('general.inc.php');
			extract($_SESSION);
			if(isset($login)){
				if($login==1 && $lvl==2){
			$driver	= new driver;

			if(isSet($_GET['action'])): //Checking if form has been submitted
				//Clean the action data value, to extract the exact form name
				$form	=	$driver->clean($_GET['action'],1,1);
				switch($form): //Check which form has been submitted i.e users from, clients form, settings form or transactions form
				CASE 'user':
					//Call the user's form processor i.e function update_user
					update_user();
				break;
				CASE 'transaction':
					//Call the transaction's form processor i.e function update_transaction
					update_transaction();
				break;
				CASE 'settings':
					//Call the setting's form processor i.e function update_settings
					update_settings();
				break;
				CASE 'client':
					//Call the client's form processor i.e function submit_update_client
					submit_update_client();
				break;
				default:
					//Call the function to indicate that there is no form that has been submitted i.e no_form
					no_form();
					break;
				endswitch;
			else://If no form has been submitted
				//Call the function to indicate that there is no form that has been submitted i.e no_form
				no_form();
			endif;//End check whether form submission has been done
			}else{
				echo '<p>Please make sure you\'ve administrator access. <a href="../">Login</a></p>';
				}
			}else{
				echo '<p>Please make sure you\'re authenticated or logged.<a href="../">Login</a></p>';	
				}
			function update_user(){}
			function update_transaction(){
			global $driver;
				if(isSet($_POST)){
				$query_trans_begin 	 ="UPDATE transactions SET ";
				$query_clients_begin ="UPDATE client_transactions SET ";
				$query_trans_mid 	 = null;
				$query_clients_mid	 = null;
					foreach($_POST as $key=>$value){
							//echo '$key '.$key.'<br/>';
						if(!empty($key) && !empty($value)):
							if($key===strtolower('prp')){
							$query_trans_mid.=",principal='$value'";
							$query_clients_mid.=",principal='$value'";
							$interest = (intRate/100)*$value; //This is the new interest
							}
							if($key===strtolower('interest')){
								$query_trans_mid.=",interest='$value'";
								$query_clients_mid.=",interest='$value'";
							}
							if($key===strtolower('arrears')){
								$query_trans_mid.=",arrears='$value'";
							}							
							if($key===strtolower('amount')){
								$query_trans_mid.=",amount='$value'";
								$query_clients_mid.=",amount='$value'";					
							}
							if($key===strtolower('cbal')){
								$query_trans_mid.=",c_balance='$value'";
							}
							if($key===strtolower('user')){
							$query_trans_mid.=",user='$value'";
							}
							if($key===strtolower('transid')){
							$query_trans_end=" WHERE id='$value' ";
							$query_clients_end=" WHERE id='$value' ";
							}
						endif;
					}
						$query_trans_mid		=	explode(',',$query_trans_mid);// Convert the string into array
						$query_trans_mid		=	implode(', ',$query_trans_mid);// Convert the array into a well formatted string
						$query_trans_mid		=	ltrim($query_trans_mid,",");//Remove the first "," at the beginning of the string
						$full_qury_trans		=	$query_trans_begin.$query_trans_mid.$query_trans_end; 
						$query_clients_mid		=	explode(',',$query_clients_mid); // Convert the string into array
						$query_clients_mid		=	implode(', ',$query_clients_mid);// Convert the array into a well formatted string
						$query_clients_mid		=	ltrim($query_clients_mid,",");//Remove the first "," at the beginning of the string
						$full_qury_clients		=	$query_clients_begin.$query_clients_mid.$query_clients_end;
						//	echo '<p>'.$full_qury_trans.'</p>';
						//echo '<p>'.$full_qury_clients.'</p>';
					if($driver->perform_request($full_qury_trans) and $driver->perform_request($full_qury_clients)){
								echo '<p class="success">Transaction modified.</p>';
					}else{
						echo mysql_error();
						}
				}else{
					no_form();
				}
			}
			function update_settings(){}
			function submit_update_client(){}
			function no_form(){}
?>