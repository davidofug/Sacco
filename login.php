<div class="logon">
	<div id="enclosure">
		<?php
			include_once('system/validation.php');
			include_once('system/db_obj.php');
			$microfin	=	 new PHP_VALIDATE;
			if(isset($_POST['ulogin'])){
				$uname	= $_POST['uname'];
				$upass	= $_POST['upass'];
				$uname	= $microfin->clean($uname,1);
				$upass	= $microfin->clean($upass,1);
				if(empty($uname) || empty($upass)){
					$error = '<span class="error">Enter your login details</span>';
				}else if(!$microfin->num_and_alpha($uname) || !$microfin->num_and_alpha($upass)){
					$error = '<span class="error">Wrong username or password!</span>';
				}else{
					$driver = new driver;

									$uname	=	mysql_real_escape_string($uname);
									$upass	= 	mysql_real_escape_string($upass);
									$upass	=	md5($upass);
									$sql="SELECT * FROM users WHERE uname='$uname' AND upassword='$upass'";
									$user = $driver->perform_request($sql) or $error='SQL ERROR <span class="error">'.mysql_error().'</span>';
									if(mysql_num_rows($user)>1){
										$error	= '<span class="error"><b>Wrong username or password</b></span>';
										}else if(mysql_num_rows($user)<=0){
											$error	= '<span class="error"><b>Wrong username or password</b></span>';
											}else{
												$user_details	= $driver->load_data($user,MYSQL_ASSOC);
												$user_details	=	extract($user_details);
												session_start();
												$_SESSION['login']	=	true;
												$_SESSION['name'] 	= $name;
												$_SESSION['lvl']	= $model;
												if($model==1){
													header('location:starter.php');
												}else if($model==2){
													header('location:administrator/');
												}else if($model==3){
													header('location:clients/');
												}else{
													$error='<span class="error">LOGIN FAILURER</span>';
													}
												}
											}
						}
			if(isSet($error) || !empty($error)){
				echo $error;
				}		
		?>
		<form action="" name="loginform" id="loginform" method="post">
			<p>Sign in with your</p>
			<p><b>Microfin account</b></p>
			<div class="element"><label for="uname">username: </label><input type="text" name="uname" id="uname" class="required text" title="Enter username" /></div>
			<div class="element"><label for="upass">password: </label><input type="password" name="upass" id="upass" class="required text" title="Enter password" /></div>
			<p>&nbsp;</p>
			<p><input type="submit" name="ulogin" id="ulogin" class="button" value="Sign in" /></p>
		</form>
</div>
</div>
