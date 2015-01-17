<?
	$server	=	$_SERVER['SERVER_NAME'];
	ini_set('date.timezone','Africa/Nairobi');
	require_once('../system/db_obj.php');
	$driver	=	new driver;
	$link = $driver->con_To_Server(SERVER,USERNAME,PASSWORD,DATABASE);	
	session_start();
	extract($_SESSION);
	if(isset($login)){
		if($login==1 && $lvl==2){
		if(isSet($_GET['form'])){
		$section = trim(strip_tags(stripslashes($_GET['form'])));
			switch($section):
				CASE 'settings':
					$intrateold	=	trim(strip_tags(stripslashes($_POST['intrateold'])));
					//$syscashold	=	trim(strip_tags(stripslashes($_POST['syscashold'])));
					$maxloanold =	trim(strip_tags(stripslashes($_POST['maxloanold'])));
					$loanold	=	trim(strip_tags(stripslashes($_POST['loanold'])));
					$mincashold =	trim(strip_tags(stripslashes($_POST['mincashold'])));
					$sysnameold =	trim(strip_tags(stripslashes($_POST['sysnameold'])));
					$intrate	=	trim(strip_tags(stripslashes($_POST['intrate'])));
					//$syscash	=	trim(strip_tags(stripslashes($_POST['syscash'])));
					$maxloan 	=	trim(strip_tags(stripslashes($_POST['maxloan'])));
					$loan		=	trim(strip_tags(stripslashes($_POST['loan'])));
					$mincash	=	trim(strip_tags(stripslashes($_POST['mincash'])));
					$sysname 	=	trim(strip_tags(stripslashes($_POST['sysname'])));
					$updater	=	trim(strip_tags(stripslashes($_SESSION['name'])));
					
					//Capture new settings
					$intrate	=	($intrateold===$intrate)?$interateold:$intrate;
					//$syscash	=	($syscashold==$syscash)?$syscashold:$syscash;
					$maxloan	=	($maxloanold===$maxloan)?$maxloanold:$maxloan;
					$loan		=	($loanold===$loan)?$loanold:$loan;
					$mincash	=	($mincashold===$mincash)?$mincashold:$mincash;
					$sysname	=	($sysnameold===$sysname)?$sysnameold:$sysname;
					//Just in case the new settings come with empty fields from user input
					$intrate	=	(empty($intrate) or $intrate==0)?$intrateold:$intrate;
					//$syscash	=	(empty($syscash) or $syscash='' or $syscash==0)?$syscashold:$syscash;
					$maxloan	=	(empty($maxloan) or $maxloan==0)?$maxloanold:$maxloan;
					$loan		=	(empty($loan) or $loan==0)?$loanold:$loan;
					$mincash	=	(empty($mincash) or $mincash==0)?$mincashold:$mincash;
					$sysname	=	(empty($sysname) or $sysname==0)?$sysnameold:$sysname;
						$date	=	date('m-d-Y H:i:s');
						$sqlupd		=	"UPDATE settings SET usermodified='$updater',interestrate='$intrate',maxloan='$maxloan',loanduration='$loan',leastcash='$mincash',systemname='$sysname'";
					if($driver->perform_request($sqlupd)){
						echo '<p class="success">Settings update successful</p>';
						echo '<p><a href="settings.php">View Current settings</p>';
					}else{
						die('<p class="error">Error: '.mysql_error().'</p>');
					}
				break;
				CASE 'newacc':
					
					$name		=	trim(strip_tags(stripslashes($_POST['name'])));
					$uname		=	trim(strip_tags(stripslashes($_POST['uname'])));
					$password	=	trim(strip_tags(stripslashes(md5($_POST['password']))));
					$cpassword	=	trim(strip_tags(stripslashes(md5($_POST['cpassword']))));
					$acl		=	trim(strip_tags(stripslashes($_POST['acl'])));
					$date		=	date('Y-m-d');
					$sqlqury	=	"INSERT INTO users VALUES('','$date','$name','$uname','$password','$acl','0000-00-00','00:00:00','1')";
					if($driver->perform_request($sqlqury)){
						echo '<p class="success">User registered successfully.</p>';
					}else{
						die('<p class="error">Error: '.mysql_error().'</p>');
					}
				CASE 'manageuser':
					$userid		=	trim(strip_tags(stripslashes($_POST['userid'])));
					$name	= trim(strip_tags(stripslashes($_POST['rlname'])));
					$acl	= trim(strip_tags(stripslashes($_POST['acl'])));
					$password=trim(strip_tags(stripslashes(md5($_POST['password']))));
					$cpassword=trim(strip_tags(stripslashes(md5($_POST['cpassword']))));
					$sqlqury	= "UPDATE users SET name='$name',upassword='$password',model='$acl' WHERE id='$userid'";
					if($driver->perform_request($sqlqury)){
						echo '<p class="success">User updated successfully.</p>';
					}else{
						die('<p class="error">Error: '.mysql_error().'</p>');
						}
				break;
				default:
			echo '<p>Please make sure you submit a form!</p>';
			echo '<p><a href="index.php" title="General settings">Reload previous settings</a></p>';
			break;
			endswitch;
		}else{
			echo '<p>Please make sure you submit a form!</p>';
			echo '<p><a href="settings.php" title="General settings">Reload previous settings</a></p>';
		}
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