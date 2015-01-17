<div class="grid">
<?php
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
ini_set('date.timezone','Africa/Nairobi');
include_once('./system/db_obj.php');
$driver	= new driver;
$today = date('Y-m-d');
$day = null;
$tot_rec	= null;
$tot_prp	= null;
$tot_int 	= null;
$tot_ref_1	= null;
$tot_ref_2 	= null;
$tot_buf_1 	= null;
$tot_buf_2 	= null;
$tot_disb 	= null;
$tot_expense= null;
$tot_deposit= null;
$tot_income = null;
$tot_rs_in	= null;
$tot_rs_out = null;
$prp_to_display =null;
$int_to_display =null;
$ref_to_display =null;
$total_prp_display = null;
$total_int_display = null;
$total_ref_display_1 = null;
$total_ref_display_2 = null;
$net_bal= null;
$day_les= null;
$more_in_ref=null;
$date_from_db ="SELECT DISTINCT(date) FROM transactions ORDER BY date DESC";
if($date_data = mysql_query($date_from_db)){
	$list ='<select name="date" id="date" class="selectbox">';
	while($row = mysql_fetch_array($date_data)){
		extract($row);
		$list .= '<option value="'.$date.'">'.$date.'</option>';
		}
		$list .='</select>';
		}
	if(isset($_REQUEST['date'])){
			$get_day	=	trim(stripslashes(strip_tags($_REQUEST['date'])));
			if(!empty($get_day)){
				$day = $get_day;
				}
$sql	= "SELECT * FROM transactions WHERE date ='$day' ORDER BY id DESC";
	}else{
					// Get the latest date from the transactions
				$latest_date	=	"SELECT date FROM transactions ORDER BY date DESC LIMIT 1";
				if(mysql_num_rows($result=mysql_query($latest_date))>0){
				$row	= mysql_fetch_array($result);
				$day	=	$row['date'];
				}
		$sql	= "SELECT * FROM transactions WHERE date ='$day' ORDER BY id";
	}
	if($result2	= mysql_query($sql)){
	if(mysql_num_rows($result2)>0){
		$less_day ="SELECT date FROM transactions WHERE date<'$day' ORDER BY date DESC LIMIT 1";
		if($quer = mysql_query($less_day)){
			while($row = mysql_fetch_array($quer)){
					$day_les	= $row['date'];				
				}
			}
$pre ="SELECT nature,c_balance FROM transactions WHERE date='$day_les' ORDER BY id DESC LIMIT 1"; //Query for the cash balance on the previous date
if($rsd = mysql_query($pre)){//If the query has been executed successfully.
if(mysql_num_rows($rsd)>0 && mysql_num_rows($rsd)<2){
	$row = mysql_fetch_array($rsd);
	$net_bal = $row['c_balance'];
}else{
	//We can not find any net closing balance so we get the initial transaction balance
	$get_initial = "SELECT * FROM settings";
	if($data = mysql_query($get_initial)){
		$row = mysql_fetch_array($data);
	$net_bal	= $row['systeminitcash'];
	}
		}
	}else{
		echo mysql_error();
		}
	$form	='<form method="post" action="'.$url.'"><div class="element"><label for="date">Select report by date: </label>'.$list.'<input type="submit" id="submit" class="button" value="View report" /></div></form>';
	$str	='<table width="90%" cellspacing="1" cellpadding="1" align="left" border="0" >';
	$str	.='<caption><p>Transaction list as of: <b>'.$day.'</b></p></caption>';
	$str	.='<tr><th colspan="9" align="center" >INFLOW</th><th colspan="5" style="color: #f03;">OUTFLOW</th><th rowspan="3">Transaction by</td></tr>';
	$str	.='<tr><th>A/C</th><th>PARTICULARS</th><th>NATURE</th><th>AMOUNT</th><th>INCOME</th><th>PRINCIPAL</th><th>INTEREST</th><th>REF</th><th>BUFFER</th><th style="color: #f03;">DISBURS'."'".'NT</th><th style="color: #f03;">EXPENSE</th><th style="color: #f03;">BUFFER</th><th style="color: #f03;">REF</th><th>CASH BALANCE</th></tr>';
	$str	.='<tr><td>CASH</td><td>Openning cash balance</td><td>CASH</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td><b>'.$net_bal.'</b></td></tr>';	
		while($row	= mysql_fetch_array($result2)){

if($row['nature']	=== 'disb'){
$str	.='<tr style="color: #f03;"><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['principal'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
$tot_disb	=	$tot_disb+$row['principal'];
}
if($row['nature']===strtoupper('expense')){
	$str	.='<tr style="color: #f03;"><td>'.strtoupper($row['acnumber']).'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
	$tot_expense	=	$tot_expense+$row['amount'];
}
if($row['nature']===strtoupper('income')){
	$str	.='<tr><td>'.strtoupper($row['acnumber']).'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
	$tot_income	=	$tot_income+$row['amount'];
}
if($row['nature']===strtoupper('deposit')){
	$str	.='<tr><td>'.strtoupper($row['acnumber']).'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
	$tot_deposit	=	$tot_deposit+$row['amount'];
}
if($row['r_type']==1){
		$str	.='<tr><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';	
		$tot_ref_1	=	$tot_ref_1+$row['amount'];
	}else if($row['r_type']==2){
		$str	.='<tr style="color: #f03;"><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';	
		$tot_ref_2	=	$tot_ref_2+$row['amount'];																									
}
if($row['nature']===strtoupper('cash')){

	if($row['b_type']==1){
		$str	.='<tr style="color: #f03;"><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';	
			$tot_buf_1	=	$tot_buf_1+$row['amount'];
		}
	else if($row['b_type']==2){
		$str	.='<tr><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
			$tot_buf_2	=	$tot_buf_2+$row['amount'];
		}
}

if($row['nature']==='recovery'){
			if($row['principal']==0){
					$prp_to_display	= '-';
					}else{
						$prp_to_display=$row['principal'];
						}
				if($row['interest']==0){
					$int_to_display='-';
					}else{
						$int_to_display=$row['interest'];
						}
				if($row['arrears']==0){
					$ref_to_display='-';
					}else{
						$ref_to_display=$row['arrears'];
						$tot_ref_1+=$row['arrears'];
						}
	$str.='<tr><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>'.$row['amount'].'</td><td>'.$row['amount'].'</td><td>'.$prp_to_display.'</td><td>'.$int_to_display.'</td><td>'.$ref_to_display.'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';
	$tot_rec=$tot_rec+$row['amount'];
	$tot_prp=$tot_prp+$row['principal'];
	$tot_int=$tot_int+$row['interest'];
	//$tot_ref_2=$tot_ref_2+$row['arrears'];
	
	if($tot_prp==0){
		$total_prp_display='-';
		}else{
			$total_prp_display=$tot_prp;
			}
	if($tot_int==0){
		$total_int_display='-';
		}else{
			$total_int_display=$tot_int;
			}
	if($tot_ref_1==0):
		$tot_ref_1='-';
	else:
		$tot_ref_display_1=$tot_ref_1;
	endif;
	if($tot_ref_2==0){
		$total_ref_display_2='-';
		}else{
			$total_ref_display_2=$tot_ref_2;
			}
		}
	}
$str.='<tr><th colspan="3">TOTOALS</th><th>'.($tot_rec+$tot_deposit).'</th><th>'.($tot_income).'</th><th>'.($total_prp_display).'</th><th>'.($total_int_display).'</th><th>'.($tot_ref_1).'</th><th>'.($tot_buf_2).'</th><th style="color:#f03;">'.($tot_disb).'</th><th style="color:#f03;">'.($tot_expense).'</th><th style="color:#f03;">'.($tot_buf_1).'</th><th style="color:#f03;">'.($tot_ref_2).'</th><th>&nbsp;</th><td>&nbsp;</td></tr>';
$str.='</table>';
echo '<div class="form">'.$form.'</div>';
echo $str.'<br/><br/>';
}
$summary  ='<table style="dailysummer" width="40%" cellspacing="1" cellpadding="3" border="0" align="left" >';
$summary .='<caption><b>DAILY SUMMARY</b></caption>';
$summary .='<tr><th colspan="5" align="left">INFLOWS</th></tr>';
$summary .='<tr><td>&nbsp;</td><td>OPENING CASH BALANCE</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$net_bal.'</td></tr>';
if($tot_deposit>0){
$summary .='<tr><td>&nbsp;</td><td>DEPOSITED</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_deposit.'</td></tr>';	
}
if($tot_prp>0){
$summary .='<tr><td>&nbsp;</td><td>PRINCIPAL AMOUNT</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_prp.'</td></tr>';
}
if($tot_int>0){
$summary .='<tr><td>&nbsp;</td><td>INTEREST</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_int.'</td></tr>';
	}
if($tot_ref_1>0){
$summary .='<tr><td>&nbsp;</td><td>REFUNDS/BALANCE</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_ref_1.'</td></tr>';
}
if($tot_buf_2>0){
$summary .='<tr><td>&nbsp;</td><td>CASH BUFFER IN</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_buf_2.'</td></tr>';
}
if($tot_income>0){
$summary .='<tr><td>&nbsp;</td><td>INCOME</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_income.'</td></tr>';
}		
$summary .='<tr class="sum_totals"><td>&nbsp;</td><th>TOTAL INFLOWS</th><td>&nbsp;</td><td>&nbsp;</td><th align="right">'.$inflow=($net_bal+$tot_deposit+$tot_prp+$tot_int+$tot_ref_1+$tot_buf_2+$tot_income).'</th></tr>';
$summary .='<tr class="out"><th colspan="5" align="left">OUT FLOWS</th></tr>';
if($tot_disb>0){
$summary .='<tr class="out"><td>&nbsp;</td><td>LOAN DISBURSEMENT</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_disb.'</td></tr>';
} if($tot_expense>0){
$summary .='<tr class="out"><td>&nbsp;</td><td>EXPENSES</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_expense.'</td></tr>';
} if($tot_buf_1>0){
$summary .='<tr class="out"><td>&nbsp;</td><td>CASH BUFFER OUT</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_buf_1.'</td></tr>';
} if($tot_ref_2>0){
$summary .='<tr class="out"><td>&nbsp;</td><td>REFUNDS/BALANCE</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">'.$tot_ref_2.'</td></tr>';
}
$summary .='<tr class="out"><td class="sum_totals">&nbsp;</td><th>TOTAL OUT FLOWS</th><td>&nbsp;</td><td>&nbsp;</td><th align="right">'.$outflow=($tot_disb+$tot_expense+$tot_buf_1+$tot_ref_2).'</th></tr>';
$summary .='<tr><td >&nbsp;</td><th>NET CLOSING CASH BALANCE</td><td>&nbsp;</td><td>&nbsp;</td><th align="right">'.$net=($inflow-$outflow).'</th></tr>';
$summary .='</table>';
	echo $summary;
}else{
	echo '<p align="center" style="color: #f03;">Sorry there isn\'t any transaction made yet!</p>';
}
?>
</div>