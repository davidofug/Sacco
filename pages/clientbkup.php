<div class="grid">
<?php
	ini_set('date.timezone','Africa/Nairobi');
	include_once('./system/generic.inc.php');
    $client = new Client($_GET['acnumber']);
    $client->client_transactions();
    if($client->account<=0){
?>
<h3 class="notice">Please <a href="starter.php?page=clients.php" title="list clients" >click here </a>to choose a client</h3>
<?php
        exit(0);
    }
?>
<div class="breif"> <div class="info">NAME:<?php echo $client->name; ?></div><div class="info"> TEL: <?php echo $client->phone; ?></div><div class="info"> A/C NUMBER: <?php echo $client->account; ?></div>
</div>
<div class="breif">
   <?php if($client->arrears>0): ?>
    ARREARS:  <?php echo $client->arrears; ?>
   <?php endif; ?>
   <?php if($client->disbursed>0): ?>
    DISBURSED: <?php echo $client->disbursed; ?>
   <?php endif; ?>
   <?php if($client->recovered>0): ?>
    RECOVERED: <?php echo $client->recovered; ?>
   <?php endif; ?>
   <?php if($client->refunded>0): ?>
    REFUNDED: <?php echo $client->refunded; ?>
   <?php endif; ?>
</div>
<div class="leftcol">
<ul class='tabs'>
    <li><a href='#loaning'>Disburse</a></li>
    <li><a href='#recovering'>Recover</a></li>
    <li><a href='#refunding'>Give refund</a></li>
	<li><a href="#receiverefund">Receive refund</a></li>
</ul>
<div class="wide" id="loaning"><h2>DISBURSE</h2>
    <?php if($client->arrears>0): ?>
    <div class="warning">
        <p>The system can not allow to receive a loan until the total balance of <?php echo $client->arrears; ?>  is cleared.</p>
        <a href="#" title="See summary" class="custombutton" id="arrearsummary" >See summary </a>
        <a href="#" title="Give loan" class="custombutton" id="giveloan" >Continue to give loan</a>
    </div>
    <?php endif; ?>
	<div class="loans"><h3>General Loan <a href="#">Click to switch to coded loan</a></h3>
	<form action="process/loan.php" method="post" >
        <input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />
        <div><label>Name: </label> <input type="text" readonly name="name" id="name" value="<?php echo $client->name;?>" /></div>
        <div><label>Account: </label>  <input type="text" readonly name="accountnumber" id="accountnumber" value="<?php echo $client->account; ?>" /></div>
        <input type="hidden" name="accountant" id="accountant" value="<?php echo $_SESSION['name']; ?>" />
		<div><label>Amount</label><input type="text" name="amount" id="amount" class="textbox" /></div>
		<div class="clear"></div>
		<div><input type="submit" name="submit" id="submit" class="button" value="submit" /></div>			
	</form>
	</div>
	<div class="loans"><h3>Coded Loan <a href="#">Click to switch to general loan</a></h3>
	<form action="process/codedloan.php" method="post" >
        <input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />
        <div><label>Name: </label> <input type="text" readonly name="name" id="name" value="<?php echo $client->name;?>" /></div>
        <div><label>Account: </label>  <input type="text" readonly name="accountnumber" id="accountnumber" value="<?php echo $client->account; ?>" /></div>
        <input type="hidden" name="accountant" id="accountant" value="<?php echo $_SESSION['name']; ?>" />
		<div><label>Amount</label><input type="text" name="amount" id="amount" class="textbox" /></div>
		<div><label>Duration</label><input type="type" name="duration" id="duration" class="textbox"/><span class="tip">e.g 3 implying 3months</span></div>
		<div><label>Monthly interest(%)</label><input type="type" name="interest" id="interest" value="<?php echo intRate(2); ?>" class="textbox"/><span class="tip">Default is 11.1%, else change.</span></div>
		<div class="clear"></div>
		<div><input type="submit" name="submit" id="submit" class="button" value="submit" /></div>			
	</form>
	</div>

</div>
<div class="wide"  id="recovering"><h2>Recover</h2>
<form action="process/recover.php" method="post" >
<input type="hidden" name="accountnumber" id="accountnumber" value="<?php echo $client->account; ?>" />
<input type="hidden" name="accountant" id="accountant" value="<?php echo $_SESSION['name']; ?>" />
<?php
    $tot_prp = null;
    $tot_int = null;
    $tot_bal = null;
    $prpbal	 = null;
    $intbal	 = null;
    $sql	=	"SELECT * FROM client_transactions WHERE nature='disb' AND acnumber='$client->account' AND balance>0 ORDER BY id DESC";
    if($results	= $client->perform_request($sql)):
    $num	=	mysql_num_rows($results);
    if($num>0):
    $unrecovered	 =	'<p>Acccount number <b>'.$client->account.'</b> has <b>'.$num.'</b> recoverable disbursements!</p>';
    $unrecovered	.=	'<table width="95%" align="center"><tr><td><b>Select</b></td><td><b>Date Acquired</b><td><b>Principal</b></td>';
    $unrecovered	.=	'<td><b>Interest</b></td><td><b>Amount due</b></td></tr>';
    while($row		=	$client->load_data($results,MYSQL_ASSOC)):
    extract($row);
    if($principalbal ==0):
    $principalbal = 'rec\'vrd';
    endif;
    if($interestbal ==0):
    $interestbal ='rec\'vrd';
    endif;
    $unrecovered .= '<tr><td><input type="radio" name="id" value="'.$id.'" id="loanid" class="checkbox" /></td><td>'.$date.'</td><td>'.$principalbal.'</td><td>'.$interestbal.'</td><td>'.$balance.'</td></tr>';
    $tot_prp	+=$principalbal;
    $tot_int	+=$interestbal;
    $tot_bal	+=$balance;
    $name		= $name;
    endwhile;
    $formid		=	microtime(true)*10000;
    $unrecovered	.='<tr><th colspan="2"><input type="radio" value="totrecover" name="id" class="radiobutton" />GRAND TOTALS</th><th>'.$tot_prp.'</th><th>'.$tot_int.'</th><th>'.$tot_bal.'</th></tr>';
    $unrecovered	.='<input type="hidden" name="formid" id="formid" value="'.$formid.'"/><input type="hidden" value="'.$tot_bal.'" name="totbal" />
    <input type="hidden" name="clientname" value="'.$client->name.'" />';
    $unrecovered	.='<tr><th colspan="2">TOTAL AMOUNT DEMANDED</th><th colspan="2">'.$tot_bal.'</th><td>&nbsp;</td></tr>';
    $unrecovered	.='<tr><th colspan="4">Enter amount: </th><td><input type="text" name="amount" id="amount" /></td></tr>';
    $unrecovered	.='<tr><td colspan="5" align="right"><input type="submit" value="Recover" name="Recover" /></td></tr></table>';
        echo $unrecovered;
    else:
    echo('<p class="error">Can not collect recoveries from client!</p>');
    endif;
    else:
    echo('<p class="error">SQL Error occured: <br/>'.mysql_error().'</p>');
    endif;

?>
</form>
</div>
<div class="wide"  id="refunding"><h2>Give refund to client</h2>
<form action="process/refund.php" method="post" >
<input type="hidden"  name="refund" id="refund" value="1" />
<input type="hidden" name="accountnumber" id="accountnumber" value="<?php echo $client->account; ?>" />
<input type="hidden" name="accountant" id="accountant" value="<?php echo $_SESSION['name']; ?>" />
<?php
    $tot_ref = null;
    $sql	=	"SELECT * FROM client_transactions WHERE acnumber='$client->account' AND refund>'0' ORDER BY id DESC";
    if($results	= $client->perform_request($sql)){
    $num	=	mysql_num_rows($results);
    if($num>0){
    $refundable	= 	'<h3>Client\'s refunds\' list below</p></h3>';
    $refundable	.=	'<table width="95%" align="center"><tr><th>Select one</th><th>Date</th><td>Amount for refund</th></tr>';
    while($row		=	$client->load_data($results,MYSQL_ASSOC))
    {extract($row);
    if($refund>0):
    $refundable .= '<tr><td><input type="radio" value="'.$id.'" name="refid" class="radiobutton" /></td><td>'.$date.'</td><td>'.$refund.'</td></tr>';
    endif;
    $tot_ref	+=	$refund;
    }
    $formid		=	microtime(true)*10000;
    $refundable	.='<tr><th><input type="radio" value="totrefund" name="refid" class="radiobutton" /></th><th>GRAND TOTAL</th><th>'.$tot_ref.'</th></tr>';
    $refundable .='<input type="hidden" name="formid" id="formid" value="'.$formid.'"/><input type="hidden" value="'.$tot_ref.'" name="totrefund" /><input type="hidden" name="clientname" value="'.$client->name.'" />';
    $refundable	.='<tr><th>Enter amount: </th><td colspan="2"><input type="text" name="amount" id="amount" /></td></tr>';
    $refundable	.='<tr><td colspan="3" align="right"><input type="submit" value="Refund" name="Refund" /></form></tr></table>';
    echo $refundable;
    }else{
    echo('<p class="error">You can not give this client refunds.</p>');
    }
    }else{
    echo('<p class="error">SQL Error occured: '.mysql_error().'</p>');
    }
    ?>
</div>
<div class="wide"  id="receiverefund"><h2>Receieve refunds</h2>
<form action="process/refund.php" method="post" >
<input type="hidden" name="refund" value="2" id="getrefund" />
<div><label>Amount</label><sinput type="text" name="amount" id="amount" class="textbox" /></div>
<div><input type="submit" name="submit" id="submit" class="button" value="submit" /></div>			
</form>
</div>
</div>
<div class="rightcol">
    <div class="accordion vertical">
<?php if($client->accountbal>0): ?>
    <div class="sammary section" id="account"><h2><a href="#account">Account balance</a></h2>
        <?php $client->accountbalhistory; ?>
    </div>
<?php endif; ?>
<?php    if($client->disbursed>0): ?>
<div class="sammary section" id="loans"><h2><a href="#loans">Disbursements</a></h2>
<table width="90%" border="1" align="center">
<tr><th>Date</th><th>Amount</th></tr>
<?php echo $client->disbursementhistory; ?>
</table>
</div>
<?php endif; ?>
<?php if($client->recovered>0): ?>
<div class="sammary section" id="recoveries">
    <h2><a href="#recoveries">Recoveries</a></h2>
    <table width="90%" border="1" align="center">
        <tr><th>Date</th><th>Amount</th></tr>
        <?php echo $client->recoverieshistory; ?>
    </table>
</div>
<?php endif; ?>
<?php if($client->refunded>0): ?>
<div class="sammary section" id="refunds">
    <h2><a href="#refunds">Refunded to client</a></h2>
<table width="90%" border="1" align="center">
<tr><th>Date</th><th>Amount</th></tr>
  <?php echo $client->refundedhistory; ?>
</table>
</div>
<?php endif; ?>
<?php if($client->arrears>0): ?>
<div class="sammary section" id="arrears"><h2><a href="#arrears">Arrears</a></h2>
<table width="90%" border="1" align="center">
<tr><th>Date</th><th>Amount</th></tr>
   <?php echo $client->arrearshistory; ?>
</table>
</div>

<?php endif; ?>
   </div><!--accordion stops here -->
</div>
</div>