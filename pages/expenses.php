<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 3/17/13
 * Time: 4:52 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!--Begin expenses form-->
<div id="expense_s_container"><form id="expenses" method="post" action="process/process.php?form=exp"><fieldset><ol>
    <input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />
    <li><label for="exptype">Type : <em><img src="templates/microfin/imgs/star.gif"/></em> </label><select name="exptype" id="exptype" class="required">
        <option value="">Select expense</option><option value="airtime">Artime</option><option value="transport">Transport</option>
        <option value="fuel">Fuel</option><option value="accomodation">Accomodation</option><option value="rent">Rent</option><option value="office">Office</option>
        <option value="general">General</option><option value="salaries">Salaries</option><option value="wages">Wages</option><option value="electricity">Electricity</option>
        <option value="water">Water</option><option value="stationery">Satationery</option><option value="legal">Legal</option><option value="public relations">Public relations</option>
        <option value="bank charges">Bank charges</option><option value="repeir & maintanence">Repair & Maintanence</option><option value="others">Others</option></select></li>
    <li><label for="expparticulars">Particulars : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="expparticulars" id="expparticulars" class="required" /></li>
    <li><label for="expamount">Amount : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="expamount" id="expamount" class="required" /></li>
    </ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="expsubmit" id="expsubmit" /></fieldset>