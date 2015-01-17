<div class="deposit_container">
	<script type="text/javascript">
		$('document').ready(function(){
			$('#accountnumber').css('display','none');
			$('#accounttype').change(function(){
				if($(this).val()==3){
					$('#accountnumber').css('display','block');
				}else{
					$('#accountnumber').css('display','none');
				}
			});
		});
	</script>
	<form method="post" action="process/deposit.php" id="deposit">
    <input type="hidden" name="formid" id="formid" value="<?php echo date('YmdHis');?>" />
		<fieldset>
		<ol>
			<li><label for="accounttype">Account type</label>
				<select name="accounttype" id="accounttype">
					<option value="">Choose account type</option>
					<option value="-1">Reserve</option>
					<option value="-2">Top OP balance</option>
					<option value="3">Client</option>
				</select>
			</li>
			<li id="accountnumber"><label for="account">Account number</label>
				<input type="text" name="account" id="account" class="required textbox" />
			</li>
			<li><label for="amount">Amount</label>
				<input type="text" name="amount" id="amount" class="required textbox" />
			</li>
		</ol>
		</fieldset>
		<fieldset class="submit">
			<input type="submit" name="submit" value="submit" />
		</fieldset>
	</form>
</div>