<div class="deposit_container">
	<script type="text/javascript">
		$('document').ready(function(){
			$('#clientnumber').css('display','none');
			$('#account').change(function(){
				if($(this).val()==2){
					$('#clientnumber').css('display','block');
				}else{
					$('#clientnumber').css('display','none');
				}
			});
		});
	</script>
	<form method="post" action="process/withdraw.php" id="withdraw">
    <input type="hidden" name="formid" id="formid" value="<?php echo date('YmdHis');?>" />
		<fieldset>
		<ol>
			<li><label for="account">Account type</label>
				<select name="account" id="account">
					<option value="">Choose account</option>
					<option value="1">Reserve</option>
					<option value="2">other</option>
				</select>
			</li>
			<li id="clientnumber">
				<label for="clientaccount">Client account</label><input type="text" name="clientaccount" id="clientaccount" class="required textbox" /><br/>
				<span style="display:block; margin-left: 210px; font-size:.8em">
				In the account box above, enter the right account number for a member. 
				<br/>Enter the account number only if you are withdrawing from a member's account.</span>
			</li>
			<li><label for="amount">Amount to withdraw</label>
				<input type="text" name="amount" id="amount" class="required textbox" />
			</li>
		</ol>
		</fieldset>
		<fieldset class="submit">
			<input type="submit" name="submit" value="submit" />
		</fieldset>
	</form>
</div>