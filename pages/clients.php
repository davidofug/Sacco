<div class="grid">
<?php
	$url 		=	(!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$filterForm	=	'<div id="filterForm"><form method="post" name="filterForm" action=""><label for="filter" >Search client by Account Number: </label>
					<input type="text" name="filter" id="filter" class="text" /><input type="submit" value="Search" /></form></div>';
	ini_set('date.timezone','Africa/Nairobi');
	include_once('system/db_obj.php');
	$driver	=	new driver;


					$query=null;
					$header=null;
					if(isSet($_POST['filter'])){
								$value = $_POST['filter'];
								$query	=	"SELECT * FROM clients WHERE acnumber={$value}"; 
								$header	=	'<thead><tr><th><a href="'.$_SERVER['PHP_SELF'].'?page=clients.php&arrange=nmedsc">NAME</a></th><th>A.C/NO</th><th>AGE</th><th>SEX</th><th>ADDRESS</th><th>ID.NO</th><th>P.NO</th></tr></thead><tbody>';
								$results	=	$driver->perform_request($query) or die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Clients '.mysql_error().'</p>');		
						}
					else if(isset($_GET['arrange'])){
						if($_GET['arrange']=='nmedsc'):
								$query	=	"SELECT * FROM clients ORDER BY name DESC"; 
								$header	=	'<thead><tr><th><a href="'.$_SERVER['PHP_SELF'].'?page=clients.php&arrange=nmeasc">NAME</a></th><th>A.C/NO</th><th>AGE</th><th>SEX</th><th>ADDRESS</th><th>ID.NO</th><th>P.NO</th></tr></thead><tbody>';
								$results	=	$driver->perform_request($query) or die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Clients '.mysql_error().'</p>');			
						else:
								$query	=	"SELECT * FROM clients ORDER BY name ASC"; 
								$header	=	'<thead><tr><th><a href="'.$_SERVER['PHP_SELF'].'?page=clients.php&arrange=nmedsc">NAME</a></th><th>A.C/NO</th><th>AGE</th><th>SEX</th><th>ADDRESS</th><th>ID.NO</th><th>P.NO</th></tr></thead><tbody>';
								$results	=	$driver->perform_request($query) or die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Clients '.mysql_error().'</p>');			
						endif;					
					}else{
								$query	=	"SELECT * FROM clients ORDER BY name"; 
								$header	=	'<thead><tr><th><a href="'.$_SERVER['PHP_SELF'].'?page=clients.php&arrange=nmedsc">NAME</a></th><th>A.C/NO</th><th>AGE</th><th>SEX</th><th>ADDRESS</th><th>ID.NO</th><th>P.NO</th></tr></thead><tbody>';
								$results	=	$driver->perform_request($query) or die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Clients '.mysql_error().'</p>');
						}
					if(mysql_num_rows($results)>0)
					{
						echo $filterForm;
						echo '<p><a href="?page=register.php" title="Register a new client">Register a new client</a></p>';
						echo '<table width="80%" border="0" cellspacing="0" align="center" ><caption><p><b>A list of registered clients.</b></p></caption>';
						echo $header;
						while($row	= mysql_fetch_array($results)):
							extract($row);
							echo '<tr><td><a href="starter.php?page=client.php&acnumber='.$acnumber.'" >'.$name.'</a></td><td><a href="starter.php?page=client.php&acnumber='.$acnumber.'" >'.$acnumber.'</a></td><td>'.$age.'</td><td>'.$gender.'</td><td>'.$address.'</td><td>'.$idnumber.'</td><td>'.$phnumber.'</td>';
							endwhile;
						echo '</tbody></table>';
					}else{
								$msg	=	(isset($_POST['filter']))?'<p align="center">The client with the <i>'.$_POST['filter'].'</i> account number you searched for can not found</p>':'<p align="center">The system can not load or find registered clients</p>';
								$msg	.=	'<p align="center"><a href="'.$url.'" title="reload">Try again</a> -OR- Use the register a new client link to register clients.</p>';
								echo $msg;
					}
	?>
</div>