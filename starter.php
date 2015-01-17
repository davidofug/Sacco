<?php
	// include the class 
	include_once('system/layoutEngine.php');
	include_once('system/db_obj.php');
	$cleaner = new driver;
	$tp = new templateParser('templates/microfin/index.php'); 
	session_start();
	extract($_SESSION);

	if(isset($login)){
		if($login==1 && $lvl==1){
		if(isset($_GET['page'])){
			$page	=	'pages/'.$cleaner->clean($_GET['page'],1,1);
			
			if(!empty($page)){
				if(file_exists($page)){
					$page 	=	$page;
				}else{
					$page	=	'<h3 align="center">The requested page can not be found</h1>';
					$page	.=	'<p align="center">Make sure the spelling is correct</p>';
					$page	.=	'<p align="center">Check if your url is correct.</p>';
					$page	.=	'<p align="center">It\'s possible the requested page was deleted.</p><br/><br/>';
					}
				}else{
					$page	= 'pages/front.php';
					}
				}else{
					$page	= 'pages/front.php';
					}
$tags=array('title'=>'Web based:Sacco App v1.2','plugins'=>'sections/plugins.php','branch'=>'sections/branch.php','logo'=>'sections/logo.php','head'=>'sections/heading.php',
			'navigation'=>'sections/navigation.php',
			'main_content'=>$page,
			'footer'=>'sections/footer.php'); 
			// parse template file 
			$tp->parseTemplate($tags); 
			// display generated page 
			echo $tp->display(); 			
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