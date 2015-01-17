<ul>
	<!--<li><a href="?page=user.php" title="My account">My account</a></li>-->
   <!-- <li><a href="?page=notes.php">My notes</a></li> -->
    <?php if($_SESSION['lvl'] ==1 OR $_SESSION['lvl']==2){ ?>
	<!-- <li><a href="?page=groups.php" title="Manage">Groups</a></li> -->
	<li><a href="?page=clients.php" rel="3" class="clients" title="View clients list">Members</a></li>
	<li><a href="?page=buffers.php" title="Record buffers">Buffers</a></li>
	<li><a href="?page=incomes.php" title="Record incomes">Incomes</a></li>
	<li><a href="?page=expenses.php" title="Record expenses">Expenses</a></li>
	<!--<li><a href="#" class="refunds" title="Collect a refund">Recieve a refund</a></li>-->
    <li><a href="?page=reports.php" title="Reports">Reports</a></li>
	<li><a href="?page=balancesheet.php" title="View daily transactions">Balance sheet</a></li>
	<li><a href="?page=arrears.php" title="View a list of clients with arrears">Arrears</a></li>
	<li><a href="?page=refund.php" title="View a list of clients with refunds">Refunds</a></li>
	<li><a href="?page=disblist.php" title="View a list of disbursed clients">Disbursements</a></li>
    <?php } ?>
    <?php if($_SESSION['lvl'] == 2){ ?>
     <li><a href="?page=users.php">Manage Users</a></li>
    <?php }?>
    <?php if($_SESSION['lvl']==3 ){ ?>
    <li><a href="?page=history.php">My history</a></li>
    <?php } ?>
</ul>