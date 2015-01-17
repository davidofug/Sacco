<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 3/26/13
 * Time: 11:04 PM
 * To change this template use File | Settings | File Templates.
 */
session_start();
ini_set('date.timezone','Africa/Nairobi');
include_once('../system/generic.inc.php');
$driver	= new driver;
//Start giving refund transaction

$id		= $driver->clean($_POST['refid'],1,1); //Get and sanitize the transaction id
$account=$driver->clean($_POST['accountnumber'],1,1); //Get and sanitize the account number
$amount	=$driver->clean($_POST['amount'],1,1); //Get and sanitize the amount
$name	=$driver->clean($_POST['clientname'],1,1);//Get and sanitize the client's name
$totrefund=$driver->clean($_POST['totrefund'],1,1);//Get and sanitize the total amount for refund
$accountant	= $driver->clean($_POST['accountant'],1,1); //Get and sanitize the account
$reftype    =   $driver->clean($_POST['refund'],1,1); //Get and sanitize refund type, can be 1 or 2

$allvals	= 	'';
foreach($_POST as $k=>$val):
    $allvals .=$val;
endforeach;
$formHash	= md5($allvals);
$allowAction	=	true;
if(isset($_SESSION['formHash'][$_POST['formid']]) && ($_SESSION['formHash'][$_POST['formid']] == $formHash)){
    $allowAction = false;
}
if(empty($id) or empty($account) or empty($amount) or empty($accountant)){
    echo '<p>id = '.$id.', account = '.$account.', amount = '.$amount.' and accountant = '. $accountant.'</p>';
    die('<p class="error">Please make sure the required fields are field!</p>');
}
if(empty($amount) or !is_numeric($amount)){
    die('<p class="error">Enter refund amount or make sure the entered amount is digits only! </p>');
}
    if($id == 'totrefund' and $amount>$totrefund):
        die('<p class="error">Make sure the amount does not exceed the client total refunds!</p>');
    elseif($id == 'totrefund' and $amount<$totrefund):
        die('<p class="error">Enter full amount or select a single refund!');
    elseif($id	==	'totrefund' and $amount==$totrefund):
        $cash		=	(curCash()-$amount);
    elseif($id != 'totrefund'):
        $cash		=	(curCash()-$amount);
        $sql	=	"SELECT refund FROM client_transactions WHERE id='$id'";
        if($result	=	 $driver->perform_request($sql)):
            $row	=	$driver->load_data($result,MYSQL_ASSOC);
            $refund_on_id	=	$row['refund'];
            $bal_refund	=	($refund_on_id-$amount);
        else:
            die('<p class="error">Refund process not completed the system encountered error.</p>');
            //die('<h3 class="error">SQL ERROR OCCURED, Contact Administrator with the error message below!</h3><p class="error">Refund error[1], '.mysql_error().'</p>');
        endif;
    endif;
if($amount>=curCash()){
        die('<p class="error">First top up cash balance to be able to give such '.$amount.' amount!</p>');
    }else if(curCash()<=minCash()){//Check if the current cash is less or to the minimum cash and the stop transactions
        die('<p class="error">Can not make any transactions because cash is lower than the minimum cash balance!<br/>Advised to top up balance.</p> Cash is: '.curCash().' and min Cash is: '.minCash());
    }else if(curCash()<=0){ //Check if the current cash is less or 0 and stop the transactions
        die('<p class="error">Can not make any transactions due to low cash balance!<br/>Advised to top up balance.</p>');
 }else{
        $tid		=	date('YmdHis'); //Computed transaction id
        $date		=	date('Y-m-d'); //Current date
        $sql6		=	($id=='totrefund')?"UPDATE client_transactions SET refund='0' WHERE acnumber='$account'":"UPDATE client_transactions SET refund='$bal_refund' WHERE id='$id'";
        $sql7		=	"INSERT INTO client_transactions VALUES('$tid','$date','','$account','refund','$name','','','','','','','','','$amount')";
        $sql8		=	"INSERT INTO transactions VALUES('$tid','$date','','','$account','$name','refund','','2','','','','$amount','$cash','$accountant')";
if($allowAction == true):
        $driver->perform_request($sql6) or /* Send error to super admin */ die('<p class="error">Refund process not completed the system encountered error.</p>');
        $driver->perform_request($sql7) or  /* Send error to super admin */ die('<p class="error">Refund process not completed the system encoutered an error.</p>');
        $driver->perform_request($sql8) or  /* Send error to super admin */ die('<p class="error">Refund process not completed the system encoutered an error.</p>');
       $_SESSION['formHash'][$_POST['formid']] = $formHash; //Save form data as a hash in a session
        echo '<p class="suxs">Refund transaction completed successfully!</p>';
else:
     echo '<p class="suxs">Refund transaction completed successfully!</p>';
endif;
    }
?>