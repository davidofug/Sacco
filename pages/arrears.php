<div class="grid">
<?php
$today = date('Y-m-d');
$sql	=	"SELECT * FROM client_transactions WHERE nature='disb' AND balance>0 AND '$today'>pay_date ORDER BY name";
									if($result = mysql_query($sql)){
										if(mysql_num_rows($result)>0):
												$str ='<table cellspacing="0" cellpadding="0" align="center" width="80%" border="0" >';
												$str .='<caption><p><b>A list of clients with arrears. Generated on '.$today.' </b></p></caption>';
												$str .='<tr id="tableheading"><th>A/C NUMBER</th><th>Date acquired</th><th>Expected recovery date</th><th>NAME</th><th>AMOUNT DUE</th></tr>';
											while($row = mysql_fetch_array($result)):
												$str .='<tr><td>'.$row['acnumber'].'</td><td>'.$row['date'].'</td><td>'.$row['pay_date'].'</td><td>'.$row['name'].'</td><td>'.$row['due'].'</td></tr>';
												endwhile;
												$str .='</table>';
										else:
												$str ='<p class="alert"><b>The system reports no client with arrears!</b></p>';
										endif;
										
									echo $str;
										}else{
											echo mysql_error();
							}	
				?>
</div>