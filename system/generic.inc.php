<?php
ini_set('datetime','Africa/Nairobi');
$today  = date('Y-m-d');
include_once('db_obj.php');
class Client extends driver{
    //Variables about the client
    var $account,$name,$phone,$id,$age,$gender,$address,$regdate,$sysid;
    //Variables about accountability
    var $accountbal,$disbursed,$refunded,$recovered,$arrears;
    //These variables below with only hold history data which be rendered in html chunks
    var $disbursementhistory,$arrearshistory,$accountbalhistory,$recoverieshistory,$refundedhistory;
    function client($account){
        global $today;
        //clean the given account number
        $account = isset($account)?$this->clean($account,1,1):0;
        $this->account = $account;
        $query = "SELECT * FROM clients WHERE clients.acnumber ={$account}";
        $results    = $this->perform_request($query) or die('ERR IN QUERY: '.mysql_error());
        $realdata   =   $this->load_data($results,MYSQL_ASSOC); //Get array of client data
        $this->name = $realdata['name'];
        $this->phone= $realdata['phnumber'];
        $this->id   = $realdata['idnumber'];
        $this->age  = $realdata['age'];
        $this->gender = $realdata['gender'];
        $this->address = $realdata['address'];
        $this->sysid   = $realdata['id'];
        $this->regdate = $realdata['registered'];
    }

    function client_transactions(){
        global $today;
        $query = "SELECT * FROM client_transactions WHERE acnumber={$this->account}";
        $results    = $this->perform_request($query) or die('ERR IN QUERY: '.mysql_error());
        while($realdata   =   $this->load_data($results,MYSQL_ASSOC)):
            if($realdata['nature']=='disb'){
                $this->disbursed +=$realdata['principal'];
                $this->disbursementhistory .='<tr><td>'.$realdata['date'].'</td><td>'.$realdata['principal'].'</td></tr>';
                if($realdata['pay_date']<$today):
                    if($realdata['balance']>0){
                        $this->arrears += $realdata['balance'];
                        $this->arrearshistory .='<tr><td>'.$realdata['date'].'</td><td>'.$realdata['balance'].'</td></tr>';
                    }
                endif;
            }
            if($realdata['nature']=='recovery'){
                $this->recovered += $realdata['amount'];
                $this->recoverieshistory .='<tr><td>'.$realdata['date'].'</td><td>'.$realdata['amount'].'</td></tr>';
            }
            if($realdata['nature']=='refund'){
                $this->accountbal += $realdata['refund'];
                $this->accountbalhistory .='<tr><td>'.$realdata['date'].'</td><td>'.$realdata['refund'].'</td></tr>';
                $this->refunded += $realdata['amount'];
                if($realdata['amount']>0){
                $this->refundedhistory .='<tr><td>'.$realdata['date'].'</td><td>'.$realdata['amount'].'</td></tr>';
                }
            }
        endwhile;
    }
}
function newAccount(){ //FUNCTION: newAccount USAGE: To generate the new account number for newly registered client
				global $driver;
				$sql = "SELECT acnumber FROM clients ORDER BY id DESC LIMIT 1"; //Retrieve the latest account number from clients table
				if($results	= $driver->perform_request($sql)):
					$row		=	$driver->load_data($results,MYSQL_ASSOC);
					$account	=	$row['acnumber'];
					$newac		=	$account+1;
						switch($newac):
							CASE $newac < 10:
							$newac	=	'000'.$newac;
							break;
							CASE $newac>=10 && $newac<100:
							$newac	=	'00'.$newac;
							break;
							CASE $newac>=100 && $newac<1000:
							$newac	=	'0'.$newac;
							break;
							default:
							$newac	=	$newac+1;
						endswitch;
					else: 
						die('<p class="error">Failed to generate New ACC/No: <br/>'.mysql_error().'</p>');
					endif;
			return $newac; //The generated account number
}
function intRate($type=null){ //FUNCTION: intRate USAGE: To return the set interest rate
		global $driver;
    if($type==2){
        return 11.1;
    }else{
        $sql =	"SELECT interestrate FROM settings"; //Retrieve the interest rate
        if($results	= $driver->perform_request($sql)):
            $row	= $driver->load_data($results,MYSQL_ASSOC);
            $interest = ($row['interestrate']>0)?($row['interestrate']):20;
        else:
            die('<p class="error">Interest rate Error: '.mysql_error().'</p>');
        endif;
        return $interest; //The interest rate
    }
}

function backupDir(){
	global $driver;
	$sql =	"SELECT backupdir FROM settings"; //Retrieve the backup directory name
	if($bckdir	=	$driver->perform_request($sql)):
		$row	=	$driver->load_data($bckdir);
		$dir	=	(!empty($row['backupdir']))?($row['backupdir']):'finData';//The retrieved backup directory name
	else:
			die('<p class="error">Failed to retrieve backup directory: '.mysql_error().'</p>');
	endif;
	return $dir; //The backup directory name
}
function curCash(){ //FUNCTION: curCash USAGE: To return the current or system initial cash
	global $driver;
	$sql_settings		=	"SELECT systeminitcash FROM settings"; //Retrieve system initial cash
	$sql_transactions	=	"SELECT c_balance FROM transactions ORDER BY id DESC LIMIT 1";
	if($cash	=	 $driver->perform_request($sql_transactions)):
		if($driver->numRows($cash)>0):
			$row = $driver->load_data($cash,MYSQL_ASSOC);
			$cashamount	=	($row['c_balance']>0)?($row['c_balance']):0;//Current cash balance
		else:
			if($cash 	=	$driver->perform_request($sql_settings)):
				$row	= 	$driver->load_data($cash,MYSQL_ASSOC);
				$cashamount = ($row['systeminitcash']>0)?($row['systeminitcash']):0;//System Initial Cash
			else:
				die('<p class="error">ERROR Retrieving Initial Cash <br/>'.mysql_error().'</p>');
			endif;
		endif;
	else:
		die('<p class="error">ERROR Retrieving Cash balance<br/>'.mysql_error().'</p>');
	endif;
	return $cashamount;
}

function maxLoan(){	//FUNCTION: maxLoan USAGE: To return the amount to loan
	global $driver;
	$sql	=	"SELECT maxloan FROM settings"; //Retrieve maximum loan amount to be given
	if($maxloan		=	$driver->perform_request($sql)):
		$row		=	$driver->load_data($maxloan);
		$maxloanamt	=	(($row['maxloan'])>0)?($row['maxloan']):500000;
	else:
		die('<p class="error">ERROR Retrieving Maximum Loan Amount.<br/>'.mysql_error().'</p>');
	endif;
	return $maxloanamt; //The maximum amount to loan
}

function maxLoanCoded(){	//FUNCTION: maxLoan USAGE: To return the amount to loan
    global $driver;
    $sql	=	"SELECT maxloan_coded FROM settings"; //Retrieve maximum loan amount to be given
    if($maxloan		=	$driver->perform_request($sql)):
        $row		=	$driver->load_data($maxloan);
        $maxloanamt	=	(($row['maxloan'])>0)?($row['maxloan']):500000;
    else:
        die('<p class="error">ERROR Retrieving Maximum Loan Amount.<br/>'.mysql_error().'</p>');
    endif;
    return $maxloanamt; //The maximum amount to loan
}
function loanExpire(){ //FUNCTION loanExpire USAGE: To return the number of days a client is allowed to return the disbursed 
	global $driver;
	$sql	=	"SELECT loanduration FROM settings"; //Retrieve the maximum days the loanee will be given before being flagged as a defaulter
	if($loanexpire	=	$driver->perform_request($sql)):
		$row	=	$driver->load_data($loanexpire);
		$maxdays	=	($row['loanduration']>0)?($row['loanduration']):30; //Retrieved loan duration
	else:
		die('<p class="error">ERROR Retrieving Loan duration.<br/>'.mysql_error().'</p>');
	endif;
	return $maxdays; //The maximum days before loan is pushed to arrear
}
function minCash(){ //FUNCTION minCash USAGE: For returning the system minimum balance
		global $driver;
		$sql		=	"SELECT leastcash FROM settings";
	if($leastcash	=	$driver->perform_request($sql)):
		$row		=	$driver->load_data($leastcash);
		$mincash	=	($row['leastcash']>0)?($row['leastcash']):500000;
	else:
		die('<p class="error">ERROR Retrieving minimum cash balance.<br/>'.mysql_error().'</p>');
	endif;
	return $mincash; //The minimum cash
}
function sysName(){ //FUNTION sysName USAGE: For returning the application name
	global $driver;
	$sql	=	"SELECT systemname FROM settings"; //Retrieve the system name
	if($appname	= $driver->perform_request($sql)):
		$row	= $driver->load_data($appname);
		$sysname	=	(!empty($row['systemname']))?($row['systemname']):" Microfinance ";//Retrieved system name
	else:
			die('<p class="error">ERROR Retrieving Application name.<br/>'.mysql_error().'</p>');
	endif;
	return $sysname; //The application name
}
function log_records($tzone="Africa/Nairob",$db=0,$file='',$sms=0,$email='',$code='',$msg=''){
    ini_set('date.timezone',$tzone);
    $date = date('Y-m-d');
    $time = date('H:i:s');
   if(($db>0) and !empty($msg)):
       $query   =   "INSERT INTO logrecords VALUES('','$date','$time','$code','$msg')";
       mysql_query($query) or die('Not logged to db because of '.mysql_error());
   endif;
   if(!empty($file) and !empty($msg)):
     //record error message to file
   endif;
   if(($sms>0) and !empty($msg)):
     //send sms
   endif;
   if(!empty($email) and !empty($msg)):
       //Send to email
   endif;
}

function reserve(){
	$reserve =0;
	global $driver;
	$getreserve	=	"SELECT * FROM reserve";
	try{
			$cash = $driver->perform_request($getreserve);
			while($data	= $driver->load_data($cash,MYSQL_ASSOC)):
					$reserve = $reserve+$data['amount'];
			endwhile;
	}catch(Exception $e){
		echo $e->getMessage();
		exit();
		}
		return $reserve; 
	}
?>