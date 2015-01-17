	<?
	session_start();
	extract($_SESSION);
	if(isset($login)){
		if($login==1 && $lvl==2){		

			//define the path to the server
	define('APPATH',dirname(__FILE__));
	//define the directory separater
	define('FCIPATH',str_replace('\\','/',APPATH));
	define('DS','/');
	//define execution for security
	define('_FEXEC',1);
	//define and initialise the template(layout) PATH
	$layOutPath	=	FCIPATH.DS.'templates'.DS.'microfin
	//define and initialise the template(layout) URL
	$layOutUrl	=	FCIPATH.DS.'templates'.DS.'microfin'.DS.'index.php
	//Include the class 
	require_once(FCIPATH.DS.'../system'.DS.'layoutEngine.php'); 
	// instantiate a new template Parser object 
	$tp= new templateParser($layOutUrl); 

if(isset($_GET['page'])){
			$page	=	trim(strip_tags(stripslashes($_GET['page'])));
			if(!empty($page)){
				if(file_exists($page)){
					$page 	=	$page;
				}else{
					$page	=	'<h3 align="center">The requested page can not be found</h1>
					$page	.=	'<p align="center">Make sure the spelling is correct</p>
					$page	.=	'<p align="center">Check if your url is correct.</p>
					$page	.=	'<p align="center">It\'s possible the requested page was deleted.</p><br/><br/>
					}
				}else{
					$page	= 'home.php
					}
				}else{
					$page	= 'home.php
					}
	// define parameters for the class 
	
	$tags=array('title'=>'Microfin ltd','plugins'=>'','branch'=>FCIPATH.DS.'sections'.DS.'branch.php','logo'=>FCIPATH.DS.'sections'.DS.'logo.php','head'=>'','navigation'=>FCIPATH.DS.'sections'.DS.'navigation.php',
	'arrange'=>'','search'=>'','main_content'=>$page,'footer'=>FCIPATH.DS.'sections'.DS.'footer.php'); 
	// parse template file 
	$tp->parseTemplate($tags); 
	// display generated page 
	echo $tp->display(); 	
			}else if($login==1 && $lvl==1){
				header('location:../starter.php');
			}else if($login==1 && $lvl==3){
				header('location:../clients/');
			}else{
				unset($_SESSION);
				session_destroy();
				header('Location:../');
				}
	}else{
			unset($_SESSION);
			session_destroy();
			header('Location:../');
	}
	?>