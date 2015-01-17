<!--Begin incomes form-->
<div id="income_s_container">
<form id="incomes" method="post" action="process/incomes.php">
<fieldset>
<ol>
    <input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />
    <li><label for="source">Source: <em><img src="templates/microfin/imgs/star.gif"/></em></label>
	<input type="text" name="source" id="source" class="required textbox" />
	</li>
    <li><label for="particulars">Particulars : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="particulars" id="particulars" class="required" /></li>
    <li><label for="amount">Amount : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="amount" id="amount" class="required" /></li>
</ol>
</fieldset>
<fieldset class="submit"><input type="submit" value="submit" name="submit" id="submit" /></fieldset>
</form>
</div>