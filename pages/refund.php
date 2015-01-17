<div class="grid">
<?php
ini_set('date.timezone','Africa/Nairobi');
include_once('system/db_obj.php');
$driver	=	new driver;

$today = date('Y-m-d');
		$sql = "SELECT * FROM client_transactions WHERE nature='recovery' OR nature='refund' AND refund>0 ";
		if($result = mysql_query($sql)){
			if(mysql_num_rows($result)>0):
					$str ='<table cellspacing="0" cellpadding="0" align="center" width="80%" border="0">';
					$str .='<caption><p><b>A list of clients who have refund</b></p></caption>';
					$str .='<tr><th>A/C NUMBER</th><th>Date</th><th>NAME</th><th>REFUND AMOUNT</th></tr>';
				while($row = mysql_fetch_array($result)):
					if($row['refund']>0):
					$str .='<tr><td>'.$row['acnumber'].'</td><td>'.$row['date'].'</td><td>'.$row['name'].'</td><td>'.$row['refund'].'</td></tr>';
					endif;
				endwhile;
					$str .='</table>';
		else:
					$str ='<p align="center"><b>There is no client to be a given a refund!</b></p>';
		endif;
		echo $str;
	}							
				?>
</div>
