	<?
	ini_set('date.timezone','Africa/Nairobi');
	require_once('../system/db_obj.php');
	$driver	=	new driver;
	$link = $driver->con_To_Server(SERVER,USERNAME,PASSWORD,DATABASE);	
	session_start();
	extract($_SESSION);
	if(isset($login)){
		//Verify if user has rights to this section.
		if($login==1 && $lvl==2){

			if(isSet($_GET['action']) and isSet($_GET['acc'])){
			$action = trim(stripslashes(strip_tags($_GET['action'])));
			$account = trim(stripslashes(strip_tags($_GET['acc'])));
				if(!empty($action) and !empty($account)){
					if($action==strtolower('manage')):
					//Call and run manageruser Function
						manageuser($account);
					elseif($action==strtolower('view')):
					//Call and run viewuser Function
						viewuser($account);
					elseif($action==strtolower('suspend')):
					//Call and run suspend function
						suspend($account);
					elseif($action==strtolower('terminate')):
					//Call and run terminate function
						terminate($account);
					elseif($action==strtolower('activate')):
					//Call and run activate function
						activate($account);
					endif;
				}else{
					echo '<p class="error">Please choose action <a href="users.php" title="Manage users" >List of Users</a></p>';
					}
			}else if(isSet($_GET['action']) and !isSet($_GET['acc'])){
				$action = trim(stripslashes(strip_tags($_GET['action'])));
				if($action==strtolower('newacc')):
				//Call and run creatuser Function
				createuser();
				endif;
			}else{
			$settings	=	"SELECT * FROM users";
			$results 	=	$driver->perform_request($settings) or die('<p class="error">Query error: '.mysql_error().'</p>');
			$count		=	 $driver->numRows($results);
			if($count>0){
			echo '<p><a href="manageusers.php?action=newacc" title="Create a new account" >Create a new account</a></p>';
			echo '<table width="70%" cellspacing="0" cellpadding="4" align="center" border="0">';
			echo '<tr><td>USERID</td><td>USERNAME</td><td>FULL NAME</td><td>USER GROUP</td><td>Account status</td><td>ACTIONS</td></tr>';
			while($row	=	$driver->load_data($results,MYSQL_ASSOC)):		
					if($row['model']==1){
						$model='Accountants';
					}else if($row['model']==2){
						 $model='Administrators';
					}else if($row['model']==3){
							$model='Clients';
					}
					if($row['status']==0){
						$status	='Terminated';
					}else if($row['status']==1){
						$status='Active';
					}else if($row['status']==2){
						$status='Suspended';
					}					
				echo '<tr><td>'.$row['id'].'</td><td>'.$row['uname'].'</td><td>'.$row['name'].'</td><td>'.$model.'</td><td>'.$status.'</td><td><a href="manageusers.php?action=manage&acc='.$row['id'].'" title="Manage this user" >Edit</a> | <a href="manageusers.php?action=view&acc='.$row['id'].'" title="View this user\'s profile">View Profile</a></td></tr>';
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
//Function: Manageuser, Parameter: Account, Purpose: Return a form populated by a chose user account details.
function manageuser($account){
	global $driver;
	$account = is_int($account)?$account:intval($account);
	$query 	= 	"SELECT * FROM users WHERE id='$account'";
	$result	=	$driver->perform_request($query) or die('<p class="error">Query error: '.mysql_error().'</p>');
	$rows	=	$driver->numRows($result);
	if($rows > 1){
		echo '<p class="error">System can not find user.</p>';
		}else{
			$row = $driver->load_data($result);
			echo '<p><a href="manageusers.php?action=newacc" title="Create a new account" >Create a new account</a></p>';
			echo '<fieldset><legend>Manage user '.$row['uname'].' </legend><form action="process.php?form=manageuser" method="post">';
			
			echo '<div><label for="rlname">Full Name:</label><input type="hidden" value="'.$row['id'].'" name="userid" /><input type="text" value="'.$row['name'].'" name="rlname" id="rlname" class="txtbox" /></div>';
			echo '<div class="cleared"></div>';
				if($row['model']==1):
					$model = "Accountant";
				elseif($row['model']==2):
					$model = "Administrator";
				else:
					$model = "Client/Customer";
				endif;
			echo '<p class="notice">Level now: <b>'.$model.'</b></p>';
			echo '<div><label for="lvl">Set new level:</label><select name="acl" id="acl" class="selectbox">
			<option value="">Set new level</option>
			<option value="1">Accountant</option>
			<option value="2">Administrator</option></select></div>
			<div class="cleared"></div>
			<div><label for="password">New password:</label><input type="password" name="password" id="password"  class="txtbox" /></div>
			<div class="cleared"></div>
			<div><label for="cpassword">Confirm password:</label><input type="password" name="cpassword" id="cpassword" class="txtbox" /></div>
			<div class="cleared"></div>';
			echo '<div class="buttons"><input type="submit" value="submit" name="submit" /> <a href="manageusers.php">Cancel & Reload User</a></div>
			<div class="cleared"></div></fieldset></form>';
			}
}
//End of function
//Function: Viewuser, Parameter: Account, Purpose: Return a page listing the details of a chosen user account.
function viewuser($account){
		global $driver;
	$account = is_int($account)?$account:intval($account);
	$query 	= 	"SELECT * FROM users WHERE id='$account'";
	$result	=	$driver->perform_request($query) or die('<p class="error">Query error: '.mysql_error().'</p>');
	$rows	=	$driver->numRows($result);
	if($rows > 1){
		echo '<p class="error">System can not find user.</p>';
		}else{
			$row = $driver->load_data($result);
				echo '<p>Full name: '.$row['name'].'</p>';
				echo '<p>User name: '.$row['uname'].'</p>';
					switch($row['model']):
						CASE 1:
						$group	=	" Accountants ";
						break;
						CASE 2: 
						$group	=	" Administrators ";
						break;
						CASE 3:
						$group	=	"Clients(Customers) ";
						break;
					endswitch;
				echo '<p>Group '.$group.'</p>';

	$last_login_date = (empty($row['last_login_date']) or $row['last_login_date']=="0000-00-00")?'Never logged in ': $row['last_login_date'];
	$last_login_time = (empty($row['last_logiin_time']) or $row['last_login_time']=="")?'':$row['last_login_time'];
	echo '<p>Last logged in : '.$last_login_date.' '.$last_login_time.'</p>';
					switch($row['status']):
						CASE 1:
						$status="Active";
						$stnum=1;
						$links='<p><a href="manageusers.php?action=suspend&acc='.$account.'" title="Suspend Member">Suspend</a> | 
						<a href="manageusers.php?action=terminate&acc='.$account.'" title="Terminate Member">Terminate<a></p>';
						break;
						CASE 2:
						$status="Suspended";
						$links='<p><a href="manageusers.php?action=activate&acc='.$account.'" title="Unsuspend Member">Unsuspend</a> | 
						<a href="manageusers.php?action=terminate&acc='.$account.'" title="Terminate Member">Terminate<a></p>';
						$stnum=2;
						break;
						CASE 3:
						$status="Terminated";
						break;
					endswitch;
				echo '<p>Member status : '.$status.'</p>';
				echo $links;
			}
}
//End of function
//Function: Createuser,Parameter: null, Purpose: Generate and render a form for registering a new user account
function createuser(){
	$form ='<fieldset><legend>Create a user</legend>
	<form method="post" action="process.php?form=newacc" >
	<div><label for="name">Full name: </label><input type="text" name="name" id="name" class="txtbox" /></div>
	<div class="cleared"></div>
	<div><label for="uname">Username: </label><input type="text" name="uname" id="uname" class="txtbox" /></div>
	<div class="cleared"></div>
	<div><label for="password">Password: </label><input type="password" name="password" id="password" class="txtbox" /></div>
	<div class="cleared"></div>
	<div><label for="cpassword">Confirm password: </label><input type="password" name="cpassword" id="cpassword" class="txtbox" /></div>
	<div class="cleared"></div>
	<div><label for="acl">Access level: </label><select name="acl" id="acl" class="selectbox"><option value="">Choose user access level</option>
	<option value="1">Accountant</option><option value="2">Administrator</option></select></div><div class="cleared"></div>
	<div><input type="submit" id="submit" value="Submit" name="submit" class="button"> <input type="reset" value="reset form" class="button"></div>
	<div class="cleared"></div>	
	</form></fieldset>';
	echo $form;	
}
//End of function
//Function: suspend, Parameter: account, Purpose: To suspend a user account
function suspend($account){
	global $driver;
	$suspendqury	=	"UPDATE users SET status='2' WHERE id='$account'";
	if($driver->perform_request($suspendqury)){
		echo '<p class="success">Account suspended</p>';
		}else{
			die('<p class="error">Sql Error: '.mysql_error().'</p>');
			}
	}
//End of function
//Function: terminate, Parameter: account, Purpose: To terminate a user account
function terminate($account){
	global $driver;
	$terminatequry	=	"UPDATE users SET status='0' WHERE id='$account'";
	if($driver->perform_request($terminatequry)){
		echo '<p class="success">Account terminated.</p>';
		}else{
			die('<p class="error">Sql Error: '.mysql_error().'</p>');
			}
	}
//End of function
//Function: activate, Parameter: account, Purpose: To activate, inactive or suspended accounts
function activate($account){
	global $driver;
	$terminatequry	=	"UPDATE users SET status='1' WHERE id='$account'";
	if($driver->perform_request($terminatequry)){
		echo '<p class="success">Account activated.</p>';
		}else{
			die('<p class="error">Sql Error: '.mysql_error().'</p>');
			}
}
//End of function
	
	?>