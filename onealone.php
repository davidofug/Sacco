<?php
	ini_set('date.timezone','Africa/Nairobi');
	include_once('template/db_obj.php');
			$driver	=	new driver;

						if($link === 0){
						$error = 'SERVER CONNECTION ERROR 0: <span class="error">Failed to establish a connection to the DB server.</span>';
						}else if($link === 4){
							$error = 'SERVER CONNECTION ERROR 4: <span class="error">Failed to establish a connection to the DB server.</span>';
						}else if($link === 5){
							$error = 'DATABASE SELECTION ERROR:<span class="error">Failed to select the database.</span>';
						}else{
														$sql	= "SELECT * FROM transactions ORDER BY id ASC LIMIT 0,100";
														if($result2	= mysql_query($sql)){
														$str	='<table width="50%" cellspacing="1" cellpadding="1" align="center" border="1" >';
												$str	.='<tr><th colspan="8" align="center" >RECOVERED</th><th colspan="5" style="color: #f03;">LOAN</th><th rowspan="3">Transaction by</td></tr>';
												$str	.='<tr><th>A/C</th><th>PARTICULARS</th><th>NATURE</th><th>AMOUNT</th><th>PRINCIPAL</th><th>INTEREST</th><th>REF</th><th>BUFFER</th><th style="color: #f03;">DISBURS'."'".'NT</th><th style="color: #f03;">EXPENSE</th><th style="color: #f03;">BUFFER</th><th style="color: #f03;">REF</th><th>CASH BALANCE</th></tr>';
												$str	.='<tr><td>CASH</td><td>Openning cash balance</td><td>CASH</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td><b>'.$net_bal.'</b></td></tr>';	
														while($row	= mysql_fetch_array($result2)){
														if($row['r_type']==1){
																$str	.='<tr><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';	
																$tot_ref_1	=	$tot_ref_1+$row['amount'];
														}else if($row['r_type']==2):
																$str .= '<tr style="color: #f03;"><td>'.$row['acnumber'].'</td><td>'.strtoupper($row['particulars']).'</td><td>'.strtoupper($row['nature']).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>'.$row['amount'].'</td><td style="color:#000;">'.$row['c_balance'].'</td><td style="color:#000;">'.$row['user'].'</td></tr>';	
																$tot_ref_2	=	$tot_ref_2+$row['amount'];															
															endif;
															}
												$str	.='</table>';
												echo $str;
															}
															}
														
?>