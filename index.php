<?php 
	//define the path to the server
	define('APPATH',dirname(__FILE__));
	//define the directory separater
	define('FCIPATH',str_replace('\\','/',APPATH));
	define('DS','/');
	//define execution for security
	define('_FEXEC',1);
	//define and initialise the template(layout) PATH
	$layOutPath	=	FCIPATH.DS.'templates'.DS.'microfin';
	//define and initialise the template(layout) URL
	$layOutUrl	=	FCIPATH.DS.'templates'.DS.'microfin'.DS.'index.php';
	//Include the class 
	require_once(FCIPATH.DS.'system'.DS.'layoutEngine.php'); 
	// instantiate a new template Parser object 
	$tp= new templateParser($layOutUrl); 
	// define parameters for the class
include_once('system/db_obj.php');
$cleaner = new driver;
	session_start();
    if(isSet($_SESSION['login']) and $_SESSION['login'] ==1 ):
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
     $tags=array('title'=>'Web based:Sacco App v1.2','plugins'=>'sections/plugins.php','branch'=>'sections/branch.php','logo'=>'sections/logo.php','head'=>'sections/heading.php','navigation'=>'sections/navigation.php','main_content'=>$page,'footer'=>'sections/footer.php');
    else:
  unset($_SESSION);
 session_destroy();
 $tags=array('title'=>'Web based:Sacco App v1.2','plugins'=>'','branch'=>FCIPATH.DS.'sections'.DS.'branch.php','logo'=>FCIPATH.DS.'sections'.DS.'logo.php','head'=>'','navigation'=>'', 'arrange'=>'','search'=>'','main_content'=>'pages/login.php','footer'=>FCIPATH.DS.'sections'.DS.'footer.php');
    endif;
	// parse template file
	$tp->parseTemplate($tags);
	// display generated page
	echo $tp->display(); 
?>