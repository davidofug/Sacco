	<?
	ini_set('date.timezone','Africa/Nairobi');
	require_once('../system/db_obj.php');
	$driver	=	new driver;
	$link = $driver->con_To_Server(SERVER,USERNAME,PASSWORD,DATABASE);	
	session_start();
	extract($_SESSION);
	if(isset($login)){
		if($login==1 && $lvl==2){

			if(isset($_GET['action'])=='m'){
				$settings	=	"SELECT * FROM settings";
					$results 	=	$driver->perform_request($settings) or die('<p class="error">Query error: '.mysql_error().'</p>');
					$count		=	 $driver->numRows($results);
			if($count>0){
					echo '<table width="50%" cellspacing="0" cellpadding="4" align="left" border="0">
					<form action="process.php?form=settings" method="post" id="settings" >';
					while($row	=	$driver->load_data($results,MYSQL_ASSOC)):
						$intRate	= ($row['interestrate']>0)?$row['interestrate']:20;
						$sysCash	= ($row['systeminitcash']>0)?$row['systeminitcash']:'System needs starting cash';
						$maxLoan	= ($row['maxloan']>0)?$row['maxloan']:500000;
						$loanV		= ($row['loanduration']>0)?$row['loanduration']:30;
						$minCash	= ($row['leastcash']>0)?$row['leastcash']:500000;
						$sysName	= (!empty($row['systemname']))?$row['systemname']:'Microfin Ltd';
						$bkpDir		= (!empty($row['backupdir']))?$row['backupdir']:'../bkup';
						echo '<tr><td colspan="3"><p class="note">Fields which are left blank will maintain their old settings.</p></td></tr>';
						echo '<tr><td><b>Setting</b></td><td><b>Currently set</b></td><td><b>New settings</b></td></tr>';
						echo '<tr><td><b>Interest rate</b></td><td>'.$intRate.'%</td><td><input type="hidden" name="intrateold" id="intrateold" value="'.$intRate.'"/><input type="text" name="intrate" id="intrate" class="textbox" /></td></tr>';
					//	echo '<tr><td><b>Initial Loaded cash</b></td><td>'.$sysCash.' UGX</td><td><input type="hidden" name="syscashold" id="syscashold" value="'.$sysCash.'"/><input type="text" name="syscash" id="syscash" class="textbox" /></td></tr>';
						echo '<tr><td><b>Maximum loan amount</b></td><td>'.$maxLoan.' UGX</td><td><input type="hidden" name="maxloanold" id="maxloanold" value="'.$maxLoan.'" /><input type="text" name="maxloan" id="maxloan" class="textbox" /></td></tr>';
						echo '<tr><td><b>Loan validity</b></td><td>'.$loanV.' days</td><td><input type="hidden" name="loanold" id="loanold" value="'.$loanV.'"/><input type="text" name="loan" id="loan" class="textbox" /></td></tr>';
						echo '<tr><td><b>Minimum current cash</b></td><td>'.$minCash.' UGX</td><td><input type="hidden" name="mincashold" id="mincashold" value="'.$minCash.'"/><input type="text" name="mincash" id="mincash" class="textbox" /></td></tr>';
						echo '<tr><td><b>System name</b></td><td>'.$sysName.'</td><td><input type="hidden" name="sysnameold" id="sysnameold" value="'.$sysName.'"/><input type="text" name="sysname" id="sysname" class="textbox" /></td></tr>';
						echo '<tr><td colspan="2"><b>Proceed by submitting new values</b></td><td><input type="submit" value="Submit" name="save" id="save" class="button" /></td></tr>';
						
					endwhile;
						echo '</form></table>';			
			} else{
						echo '<p class="error">show form for new entries</p>';
						}
			}else{
				$settings	=	"SELECT * FROM settings";
					$results 	=	$driver->perform_request($settings) or die('<p class="error">Query error: '.mysql_error().'</p>');
					$count		=	 $driver->numRows($results);
					if($count>0){
					echo '<table width="50%" cellspacing="0" cellpadding="4" align="left" border="0">';
					while($row	=	$driver->load_data($results,MYSQL_ASSOC)):
						$intRate	= ($row['interestrate']>0)?$row['interestrate']:20;
						$sysCash	= ($row['systeminitcash']>0)?$row['systeminitcash']:'System needs starting cash';
						$maxLoan	= ($row['maxloan']>0)?$row['maxloan']:500000;
						$loanV		= ($row['loanduration']>0)?$row['loanduration']:30;
						$minCash	= ($row['leastcash']>0)?$row['leastcash']:500000;
						$sysName	= (!empty($row['systemname']))?$row['systemname']:'Microfin Ltd';
						$bkpDir		= (!empty($row['backupdir']))?$row['backupdir']:'../bkup';
						echo '<tr><td><b>Setting</b></td><td><b>Current settings</b></td></tr>';
						echo '<tr><td><b>Interest rate</b></td><td>'.$intRate.'%</td></tr>';
						echo '<tr><td><b>Initial Loaded cash</b></td><td>'.$sysCash.' UGX</td></tr>';
						echo '<tr><td><b>Maximum loan amount</b></td><td>'.$maxLoan.' UGX</td></tr>';
						echo '<tr><td><b>Loan validity</b></td><td>'.$loanV.' days</td></tr>';
						echo '<tr><td><b>Minimum current cash</b></td><td>'.$minCash.' UGX</td></tr>';
						echo '<tr><td><b>System name</b></td><td>'.$sysName.'</td></tr>';
						echo '<tr><td><a href="settings.php?action=m" title="edit/update/modify">Modify</a></td></tr>';
					endwhile;
						echo '</table>';
					}else{
						echo '<p class="error">No settings loaded</p>';
						}
				}
			}else if($login==1 && $lvl==1){
				header('location:../starter.php');
			}else if($login==1 && $lvl==3){
				header('location:../clients/');
			}else{
				unset($_SESSION);
				session_destroy();
				header('Location:./');
				}
	}else{
			unset($_SESSION);
			session_destroy();
			header('Location:./');
	}
	?>