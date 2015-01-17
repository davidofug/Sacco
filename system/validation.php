<?php
/*
App:			Validation v1
Description:	PHP Validation class created to validate user form inputs 
Author: 		David Melbourn Wampamba
URL:			http://www.fortisart.net
Support: 		david@fortisart.net
Post comments: 	david@fortisart.net
Post comments or suggest more features
*/
	class PHP_VALIDATE{
		/*
		Method name: 	clean
		Description:	Remove unwanted spaces, convert html tags to their equivalent entities. 
						Optionally remove html,script and php tags from the input
		Return:			input
		*/
		function clean($input,$tags=0,$slashes=0){
			$input 	= trim($input);
			if($tags>0){
				$input 	=	strip_tags($input);
				}else{
					$input = htmlentities($input);
					}
				if($slashes>0){
					$input	= stripslashes($input);
					}
				return $input;
				}
		/*
		Method name:	digits
		Description:	This will validate if the input string contains digits only
		Return:			String if test passed, or 0(false if test failed)
		*/
		function digits($input){
			$input	=	$this->clean($input,1);
			if(ctype_digit($input)==true){
				return $input;
				}else{
					return 0;
					}
		}
		/*
		Method name:	number
		Description:	Checks if the string contains a numeral value e.g an interger, decimal etc
		Return:			String if test passed, 0(falase) if test failed
		*/
		function number($input){
			$input	=	$this->clean($input,1);
			if(is_numeric($input)==true){
				return $input;
				}else{
					return 0;
					}
		}
		/*
		Method name:	alpha
		Description:	Checks if the string contains alphabetic characters only
		Return:			String if test passed, 0(false) if test failed.
		*/
		 function alpha($input){
			$input	=	$this->clean($input,1);
			if(ctype_alpha($input)==true){
				return $input;
				}else{
					return 0;
					}
				}
		/*
		Method name:	num_and_alpha
		Description:	Checks if the string contains alphabetic and numeric characters only
		Return:			String if test passed, 0(false) if test failed.
		*/
		function num_and_alpha($input){
			$input	=	$this->clean($input,1);
			if(ctype_alnum($input)==true){
				return $input;
				}else{
					return 0;
					}
				}
		/*
		Method name:	url_validate
		Description:	Checks if the input is a valid url
		Return:			url if test passed, 0(false) if test failed.
		*/
		function url_validate($input){
					$input = $this->clean($input,1);
					if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $input))
					{
						return $input;
					}else{
						return 0;
						}
				}
		/*
		Method name:	username_validate
		Description:	Checks if the input is a valid username containing, alphanumeric characters with a length
						between 5 and 15 characters
		Return:			username if test passed, 0(false) if test failed.
		*/
		function username_validate($input){
				$input = $this->clean($input,1);
				if(num_and_alpha($input) and strlen($input)>=8 and strlen($input)<=15){
				return $input;
			}else{
				return 0;
				}
		}
		/*
		Method name:	password_validate
		Description:	Checks if the input is a valid password containing, alphanumeric characters with a length
						between 8 and 15 characters, with at least one digit
		Return:			password if test passed, 0(false) if test failed.)
		*/
		function password_validate($input){
			$regex ='((?=.*\d)(?=.*[a-zA-Z]).{8,15})';
			if(preg_match($regex,$input)==true){
				return $input;
				}else{
					return 0;
					}
				}
		/*
		Method name:	cleanToDb
		Description:	Clean the data before inserting in database
		Return:			Cleaned data
		*/
		function clean_for_db($input){
			return mysql_real_escape_string($input);
			}
		/*
		Method name:	date_validate
		Description:	Checks if the input is a valid date format 23/4/2006
		Return:			date if test passed, 0(false) if test failed.
		*/
		function date_validate($input){
			$regex = '(\d{1,2}\/\d{1,2}\/\d{4})';
			$input =	$this->clean($input);
					if(preg_match($regex,$input)==true){
						return $input;
						}else{
							return 0;
							}
					}
		function email_validate($input){
				if(filter_var($input,FILTER_VALIDATE_EMAIL)){
				return $input;
				}else{
					return 0;
					}
		}
		
		
	}	
	
?>