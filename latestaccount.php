		<?php
			usleep(100);
			ini_set('date.timezone','Africa/Nairobi');
			include_once('template/db_obj.php');
			$driver	= new driver;

						if($link === 0){
						$error = 'SERVER CONNECTION ERROR 0: <span class="error">Failed to establish a connection to the DB server.</span>';
						}else if($link === 4){
							$error = 'SERVER CONNECTION ERROR 4: <span class="error">Failed to establish a connection to the DB server.</span>';
						}else if($link === 5){
							$error = 'DATABASE SELECTION ERROR:<span class="error">Failed to select the database.</span>';
						}else{
				$sql = "SELECT acnumber FROM clients ORDER BY id DESC LIMIT 0,1";
				if($results	= $driver->perform_request($sql)){
					$row		=	$driver->load_data($results,MYSQL_ASSOC);
					$account	=	$row['acnumber'];
					$newac		=	$account+1;
					switch($newac){
						CASE $newac < 10:
						$newac	=	'000'.$newac;
						break;
						CASE $newac>=10 && $newac<100:
						$newac	=	'00'.$newac;
						break;
						CASE $newac>=100 && $newac<1000:
						$newac	=	'0'.$newac;
						break;
						default:
						$newac	=	$newac+1;
								}
						echo $newac;
					}else{
							die("Account error ".mysql_error());
					}
				}
?>