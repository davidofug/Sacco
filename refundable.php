<div class="datagrid">
<?php
	ini_set('date.timezone','Africa/Nairobi');
	$tot_prp=null;
	$tot_int=null;
	$tot_bal=null;
	$tot_ref=null;
	$id	=	trim(strip_tags(stripslashes($_GET['number'])));
			include_once('system/db_obj.php');
			$driver	= new driver;

			$sql	=	"SELECT * FROM client_transactions WHERE acnumber='$id' AND nature='recovery' OR nature='refund' AND refund>0 ORDER BY id";
				if($results	= $driver->perform_request($sql)){
					$num	=	mysql_num_rows($results);
					if($num>0){
					$refundable	=	'Acccount number '.$id.' has '.$num.' Refundable amount(s)';
					$refundable	.= 	'View the list below';
					$refundable	.=	'<table width="80%"><tr><td>Refund Number</td><td>Date</td><td>Refundable</td><td>Enter Amount</td>';
					while($row		=	$driver->load_data($results,MYSQL_ASSOC))
					{extract($row);
						$refundable .= '<tr><td>'.$id.'</td><td>'.$date.'</td><td>'.$refund.'</td><td><input type="text" name="amount[]" id="amount[]" /></td></tr>';
						$tot_ref	+=	$refund;
					}
					$refundable	.='<tr><td colspan="3">GRAND TOTAL</td><td>'.$tot_ref.'</td></tr>';
					$refundable	.='<tr><td colspan="3">Alternatively enter full amount: </td><td><input type="text" name="totamount" id="totamount" /></td></tr>';
					$refundable	.='<tr><td colspan="4"><input type="button" value="Refund" name="Refund" /></td></table>';
					echo $refundable;
					}else{
						$msg	= '<p class="error">Client can not be given/awarded refund.</p>';
						echo $msg;
					}
					}else{
						$msg	=	'<p class="error">SQL Error occured: '.mysql_error().'</p>';
						echo $msg;
						}
?>
</div>