<div class="grid">
<?php
$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	ini_set('date.timezone','Africa/Nairobi');
	include_once('./system/db_obj.php');
			$driver	=	new driver;

					$error			=	null;
					$day_les		= 	null;
					$list			= 	null;
					$disb_total		=	null;
					$principal		=	null;
					$interest		=	null;
					$int_total		=	null;
					$overall_total	=	null;
					$total_dm		=	null;	
					$sql_name		=	null;
					$total			=	null;
					
					$date_from_db ="SELECT DISTINCT(date) FROM client_transactions ORDER BY id DESC";
					if($date_data = $driver->perform_request($date_from_db)){
						while($row = $driver->load_data($date_data,MYSQL_ASSOC)){
							$list .= '<option value="'.$row['date'].'">'.$row['date'].'</option>';
							}
							}
						if(isset($_POST['getbyrange'])){
								$from_day	=	trim(stripslashes(strip_tags($_POST['from'])));
								$to_day		=	trim(stripslashes(strip_tags($_POST['to'])));
									if(empty($from_day) || $from_day==''){
										$error = '<p align="center" class="error">Please select date range.</p><p align="center"><a href="'.$url.'">Reload disbursement list</a></p>';
										}
									else if(empty($to_day) || $to_day==''){
										$error = '<p align="center" class="error">Please select date range.</p><p align="center"><a href="'.$url.'">Reload disbursement list</a></p>';					
									}else if($from_day>$to_day){
										$error = '<p align="center" class="error">Wrong date; between'.$from_day.' and '.$to_day.' range selected.</p><p align="center"><a href="'.$url.'">Reload disbursement list</a></p>';
										
									}else{
										$sql_name	= "SELECT * FROM client_transactions WHERE nature='disb' AND date BETWEEN '$from_day' AND '$to_day' ORDER BY name";						
										}
									}else{
									$to_day 		= 	date('Y-m-d');
									$from_day		=	mktime(0,0,0,date("m"),date("d")-29,date("Y"));
									$from_day		=	date('Y-m-d',$from_day);			
									$sql_name		=	"SELECT * FROM client_transactions WHERE nature='disb' AND date BETWEEN '$from_day' AND '$to_day' ORDER BY name ";
									}
									
									if(isSet($error)){
										echo $error;
									}else{
										$range	='<div class="element"><b>View report:</b> From: <select name="from" id="from" class="selectbox">'.$list.'</select> To: <select name="to" id="to" class="selectbox">'.$list.'</select> <input type="submit" name="getbyrange" id="getbyrange" class="button" value="Get" /></div>';
										echo '<div class="form"><form method="post" action="'.$url.'">';
										echo $range;
										echo '</form></div>';
										$all  ='<table id="summary" width="80%" cellspacing="0" cellpadding="0" border="0" align="center" >';
										$all .='<caption><b>Disbursement list</b> Beginning at: <b>'.$from_day.'</b> Ending at: <b>'.$to_day.'</b></caption>';
										$all .='<tr><th>A/C NUMBER</th><th>NAME</th><th>Date</th><th>Disbursement Amount</th><th>Interest</th><th>Total</th></tr>';
										echo $all;
										
										if($name_rs	=	mysql_query($sql_name)){
										while($row	=	mysql_fetch_array($name_rs)){
												$total = $row['principal']+$row['interest'];
												$int_total		+=$row['interest'];
												$disb_total 	+=$row['principal'];
												$overall_total	+=$total;
												echo '<tr><td>'.$row['acnumber'].'</td><td>'.$row['name'].'</td><td>'.$row['date'].'</td><td>'.$row['principal'].'</td><td>'.$row['interest'].'</td><th>'.$total.'</th></tr>';
											}
										echo '<tr><th colspan="2">TOTAL</th><th>&nbsp;</th><th>'.$disb_total.'</th><th>'.$int_total.'</th><th align="center">'.$overall_total.'</th></tr>';
										echo '</table>';
										}else{
									echo '<p class="error">Sorry there isn\'t any transaction made yet!'.mysql_error().'</p>';
									}	
								}									
				?>
</div>