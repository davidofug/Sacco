<?php
	defined('_FEXEC') or ('Access denied');
	define('APPATH',dirname(__FILE__));
?>
<!DOCTYPE html>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-language" content="en-us" />
        <meta name="author" content="http://www.fortisart.com" />
		<!--Entire system stylesheets -->
		<link rel="stylesheet" href="system/css/colorbox.css" type="text/css" media="screen" />		
		<!-- Template specific stylesheets -->
		<link rel="stylesheet" href="templates/microfin/css/design.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="templates/microfin/css/formstyles.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="templates/microfin/css/login.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="templates/microfin/css/accordion.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="templates/microfin/css/printing.css" type="text/css" media="print"  />
		<!-- Entire system javascripts -->
		<script type="text/javascript" src="system/js/jquery.min.js" ></script>
		<script type="text/javascript" src="system/js/jquery.form.js" ></script>
		<script type="text/javascript" src="system/js/jquery.validate.min.js" ></script>
		<script type="text/javascript" src="system/js/jquery.colorbox.min.js" ></script>
		<!-- Layout or Template specific javascripts -->
		<script type="text/javascript" src="templates/microfin/js/custom.jscript.js" ></script>
		<script type="text/javascript" src="templates/microfin/js/open.dialogs.js" ></script>
		<title>{title} - {branch}</title>
	</head>
	<body>
	
		<div class="header">{logo} {head}</div>
		<div class="clear"></div>
		<div class="navigation">
				{navigation}
		</div>
		<div class="clear"></div>
			<div class="main_layer">
				<div class="main_content">
					{main_content}
				</div>
			</div>
		<div class="clear"></div>
		<div class="footer">{footer}</div>
	</body>
</html>