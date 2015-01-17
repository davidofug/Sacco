	<?
	ini_set('date.timezone','Africa/Nairobi');
	require_once('../system/db_obj.php');
	$driver	=	new driver;
	$link = $driver->con_To_Server(SERVER,USERNAME,PASSWORD,DATABASE);	
	session_start();
	extract($_SESSION);
	if(isset($login)){
		if($login==1 && $lvl==2){		
	//define the path to the server
	define('APPATH',dirname(__FILE__));
	//define the directory separater
	define('FCIPATH',str_replace('\\','/',APPATH));
	define('DS','/');
	//define execution for security
	define('_FEXEC',1);
	//define and initialise the template(layout) PATH
	$layOutPath	=	FCIPATH.DS.'templates'.DS.'microfin';
	//define and initialise the template(layout) URL
	$layOutUrl	=	FCIPATH.DS.'templates'.DS.'microfin'.DS.'index.php';
	//Include the class 
	require_once(FCIPATH.DS.'../system'.DS.'layoutEngine.php'); 
	// instantiate a new template Parser object 
	$tp= new templateParser($layOutUrl); 
	if(isset($_GET['page'])){
			$page	=	trim(strip_tags(stripslashes($_GET['page'])));
			if(!empty($page)){
				if(file_exists($page)){
					$page 	=	$page;
				}else{
				$action = isSet($_GET['action'])?$_GET['action']:'';
				$action = trim(strip_tags(stripslashes($action)));
				switch($action):
					CASE 'edit':
					$page = editTransaction($_GET['trid']);
					break;
					CASE 'view':
					$page = viewTransaction($_GET['trid']);
					break;
					default:
					$page = listTransactions();
					break;
				endswitch;
					}
				}else{	
				$action = isSet($_GET['action'])?$_GET['action']:'';
				$action = trim(strip_tags(stripslashes($action)));
				switch($action):
					CASE 'edit':
					$page = editTransaction($_GET['trid']);
					break;
					CASE 'view':
					$page = viewTransaction($_GET['trid']);
					break;
					default:
					$page = listTransactions();
					break;
				endswitch;
					}
				}else{
				$action = isSet($_GET['action'])?$_GET['action']:'';
				$action = trim(strip_tags(stripslashes($action)));
				switch($action):
					CASE 'edit':
					$page = editTransaction($_GET['trid']);
					break;
					CASE 'view':
					$page = viewTransaction($_GET['trid']);
					break;
					default:
					$page = listTransactions();
					break;
					endswitch;
					}
	// define parameters for the class 
	
	$tags=array('title'=>'Microfin ltd','plugins'=>'','branch'=>FCIPATH.DS.'sections'.DS.'branch.php','logo'=>FCIPATH.DS.'sections'.DS.'logo.php','head'=>'','navigation'=>FCIPATH.DS.'sections'.DS.'navigation.php',
	'arrange'=>'','search'=>'','main_content'=>$page,'footer'=>FCIPATH.DS.'sections'.DS.'footer.php'); 
	// parse template file 
	$tp->parseTemplate($tags); 
	// display generated page 
	echo $tp->display(); 	
			}else if($login==1 && $lvl==1){
				header('location:../starter.php');
			}else if($login==1 && $lvl==3){
				header('location:../clients/');
			}else{
				unset($_SESSION);
				session_destroy();
				header('Location:../');
				}
	}else{
			unset($_SESSION);
			session_destroy();
			header('Location:../');
	}

	//Function 
	function listTransactions(){
			global $driver;
			$alltrans	=	"SELECT * FROM transactions";
			$results 	=	$driver->perform_request($alltrans) or die('<p class="error">Query error: '.mysql_error().'</p>');
			$string = '<table width="95%" align="center" border="0" cellspacing="0" cellpadding="2">
				<tr><td>TransID</td><td>TransDate</td><td>AC/NO.</td><td>Particulars</td><td>Nature</d><td>Buffer</td><td>Refund</td><td>Principal</td>
				<td>Interest</td><td>Arrears</td><td>Amount</td><td>CurrBalance</td><td>Approved/Modified by</td><td>Actions</td></tr>';
			while($row	=	$driver->load_data($results,MYSQL_ASSOC)):
				if($row['b_type']==1){
					$buffer="Out going";
				}else if($row['b_type']==2){
					$buffer="Incoming";
				}else{
					$buffer="-";
					}
					
				if($row['r_type']==1){
					$refund="Collect";
				}else if($row['r_type']==2){
					$refund="Given out";
				}else{
					$refund="-";
					}
				$amount	=	(empty($row['amount']) OR $row['amount']<=0)?'-':$row['amount'];	
				$arrears=	(empty($row['arrears']) OR $row['arrears']<=0)?'-':$row['arrears'];	
				$interest=	(empty($row['interest']) OR $row['interest']<=0)?'-':$row['interest'];	
				$principal=	(empty($row['principal']) OR $row['principal']<=0)?'-':$row['principal'];	
				$string .='<tr><td>'.$row['id'].'</td><td>'.$row['date'].'</td><td>'.$row['acnumber'].'</td><td>'.$row['particulars'].'</td><td>'.$row['nature'].'</td>
				<td>'.$buffer.'</td><td>'.$refund.'</td><td class="numbs">'.$principal.'</td><td class="numbs">'.$interest.'</td><td class="numbs">'.$arrears.'</td>
				<td class="numbs">'.$amount.'</td><td class="numbs">'.$row['c_balance'].'</td><td>'.$row['user'].'</td><td><a href="transactions.php?action=edit&trid='.$row['id'].'" title="Edit/Modify transaction">Edit</a>| <a href="transactions.php?action=view&trid='.$row['id'].'" title="View details of transaction">View</a></td>
				</tr>';
			endwhile;
			
			return $string.'</table>';
	}
	function editTransaction($transid){
		global $driver;
		$transid = (!empty($transid))?trim(stripslashes(strip_tags($transid))):0;
		$get_trxn = "SELECT * FROM transactions WHERE id='$transid'";
		$rst = $driver->perform_request($get_trxn);
		$row = $driver->load_data($rst,MYSQL_ASSOC);
		$string	='<p><a href="transactions.php?action=view&trid='.$row['id'].'" title="Edit/Modify transaction">View transaction</a></p><table class="edittransactions" width="40%" align="center" border="0" cellspacing="0" cellpadding="4">';
		$string .='<thead><tr><th>Data field</th><th>Current value</th></tr></thead>';
		$string .'<tbody>';
		if(!empty($row['acnumber'])):
		$string.='<tr><td>AC/NO:</td><td>'.$row['acnumber'].'</td></tr>';
		endif;
		if(!empty($row['particulars'])):
		$string.='<tr><td>Particulars:</td><td>'.$row['particulars'].'</td></tr>';
		endif;
		
		if(!empty($row['nature'])):
		$string.='<tr><td>Nature:</td><td>'.$row['nature'].'</td></tr>';
		endif;
		if(!empty($row['b_type'])):
			if($row['b_type']==1){
		$string.='<tr><td>Buffer: </td><td>Incoming buffer</td></tr>';
			}else{
		$string.='<tr><td>Buffer:</td><td>Outgoing buffer</td></tr>';
			}
		endif;
		if(!empty($row['r_type'])):
			if($row['r_type']==1){
			$string.='<tr><td>Refund:</td><td> Collected</td></tr>';
			}else{
			$string.='<tr><td>Refund:</td><td> Given out</td></tr>';
			}
		endif;
		if($row['principal']>0):
		$string.='<tr><td><label for="prp">Principal:</label></td><td><input type="text" name="prp" id="prp" class="txtbox" value="'.$row['principal'].'" /></td></tr>';
		endif;
		if($row['interest']>0):
		$string.='<tr><td><label for="interest">Interest:</label></td><td><input type="text" name="interest" id="interest" class="txtbox" value="'.$row['interest'].'" /></td></tr>';
		endif;
		if($row['arrears']>0):
		$string.='<tr><td><label for="arrears">Arrears:</label></td><td><input type="text" name="arrears" id="arrears" class="txtbox" value="'.$row['arrears'].'" /></td></tr>';
		endif;
		if($row['amount']>0):
		$string.='<tr><td><label for="amount">Amount:</label></td><td><input type="text" name="amount" id="amount" class="txtbox" value="'.$row['amount'].'" /></td></tr>';
		endif;
		$string.='<tr><td><label for="cbal">Cash balance:</label></td><td><input type="text" name="cbal" id="cbal" class="txtbox" value="'.$row['c_balance'].'" /></td><tr>
		<tr><td colspan="2">
			<input type="submit" value="submit" name="submit" class="button" />
			<input type="reset" value="Rest form" name="reset" class="button" />
		</td></tr></tbody></table>';
		return '<form action="process/process.php?action=transaction" method="post">'.$string.'<input type="hidden" name="user" id="user" value="'.$_SESSION['name'].'" /><input type="hidden" value="'.$row['id'].'" name="transid" id="transid" /></form>
		<p><a href="transactions.php?action=view&trid='.$row['id'].'" title="Edit/Modify transaction">View transaction</a></p>';
	}
	function viewTransaction($transid){
		global $driver;
		$transid = (!empty($transid))?trim(stripslashes(strip_tags($transid))):0;
		$get_trxn = "SELECT * FROM transactions WHERE id='$transid'";
		$rst = $driver->perform_request($get_trxn);
		$row = $driver->load_data($rst,MYSQL_ASSOC);
				$string	='<p><a href="transactions.php?action=edit&trid='.$row['id'].'" title="Edit/Modify transaction">Edit transaction</a></p><table class="edittransactions" width="40%" align="center" border="0" cellspacing="0" cellpadding="4">';
		$string .='<thead><tr><th>Data field</th><th>Current value</th></tr></thead>';
		$string .'<tbody>';
		if(!empty($row['acnumber'])):
		$string.='<tr><td>AC/NO:</td><td>'.$row['acnumber'].'</td></tr>';
		endif;
		if(!empty($row['particulars'])):
		$string.='<tr><td>Particulars:</td><td>'.$row['particulars'].'</td></tr>';
		endif;
		
		if(!empty($row['nature'])):
		$string.='<tr><td>Nature:</td><td>'.$row['nature'].'</td></tr>';
		endif;
		if(!empty($row['b_type'])):
			if($row['b_type']==1){
		$string.='<tr><td>Buffer: </td><td>Outgoing buffer</td></tr>';
			}else{
		$string.='<tr><td>Buffer:</td><td>Incoming buffer</td></tr>';
			}
		endif;
		if(!empty($row['r_type'])):
			if($row['r_type']==1){
			$string.='<tr><td>Refund:</td><td> Collected</td></tr>';
			}else{
			$string.='<tr><td>Refund:</td><td> Given out</td></tr>';
			}
		endif;
		if($row['principal']>0):
		$string.='<tr><td>Principal:</td><td>'.$row['principal'].'</td></tr>';
		endif;
		if($row['interest']>0):
		$string.='<tr><td>Interest:</td><td>'.$row['interest'].'</td></tr>';
		endif;
		if($row['arrears']>0):
		$string.='<tr><td>Arrears:</td><td>'.$row['arrears'].'</td></tr>';
		endif;
		if($row['amount']>0):
		$string.='<tr><td>Amount:</td><td>'.$row['amount'].'</td></tr>';
		endif;
		$string.='<tr><td>Cash balance:</td><td>'.$row['c_balance'].'</td><tr>
		</tbody></table><p><a href="transactions.php?action=edit&trid='.$row['id'].'" title="Edit/Modify transaction">Edit transaction</a></p>';
		return $string;
	}
?>