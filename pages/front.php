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
										$error	= 'LOGIN ERROR <span class="error">Wrong username/password</span>';
										}else if(mysql_num_rows($user)==0){
											$error	= 'LOGIN ERROR <span class="error">Wrong username/password</span>';
											}else{
												$user_details	= $driver->load_data($user,MYSQL_ASSOC);
												$user_details	=	extract($user_details);
												session_start();
												$_SESSION['login']	=	true;
												$_SESSION['name'] 	= $name;
												if($control==1){
													header('location:starter.php');
												}else if($control==2){
													header('location:admin.php');
												}else{
													$error='<span class="error">LOGIN FAILURER</span>';
													}
												}
										}
						}
			if(isset($error) || !empty($error)){
				echo $error;
				}		
		?>

		<h3>Welcome to Microfin</h3>
		<p>Click on the above links in the navigation to proceed.</p>