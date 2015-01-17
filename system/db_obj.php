<?php
//Start being including the defined database server authentication details
include_once('validation.php');
class driver extends PHP_VALIDATE{
//Method: con_To_Server USAGE: To establish a connection to the database server and return the selected database
//The method accepts 4 arguments(paremeters) i.e server,username,password and database
        var $server     = 'localhost';
        var $user       = 'root';
        var $key        = '';
        var $database   = 'sacco_db';
		function driver(){
			//Sanitize the authentication details
			$clean_server	=	$this->clean($this->server,1,1);
			$clean_user		=	$this->clean($this->user);
			$clean_key		=	$this->clean($this->key);
			$clean_db		=	$this->clean($this->database);
			//Clean after the cleaining the details that they're not empty i.e it's only the password or access key which can be empty
			if(empty($clean_server) || empty($clean_user) || empty($clean_db)){
					die('SERVER CONNECTION ERROR: <span class="error">Failed to establish a connection to the DB server.</span>');
				}else{
					$link = mysql_connect($clean_server,$clean_user,$clean_key) or die('SERVER CONNECTION ERROR: <span class="error">Failed to establish a connection to the DB server.</span>');
					$db = mysql_select_db($clean_db,$link) or die('DATABASE SELECTION ERROR:<span class="error">Failed to select the database.</span>');
					}
				return $db;
			}
		function perform_request($query=null){
			if(empty($query)){
				return 0;
				}
			else{
				return mysql_query($query);
				}
		}
		function load_data($data,$flag=null){
			if(empty($data)){
				return 0;
			}else if(empty($flag)){
				return mysql_fetch_array($data);
				}else{
					return mysql_fetch_array($data,$flag);
					}		
		}
		function con_close($link=null){
			if(empty($link)){
			return	mysql_close();
				}else{
					return mysql_close($link);
					}
		}
		function numRows($query){
			if(empty($query)):
				return 0;
				else:
				return mysql_num_rows($query);
			endif;
			}			
		function print_definitions($server, $dbname){
			echo 'SERVER: => '.$server.'<br/>';
			echo 'DATABASE: => '.$dbname;
			}
}
//$clone	= 	new driver;
//echo $clone->con_TO_Server(SERVER,USERNAME,PASSWORD,DATABASE);

?>