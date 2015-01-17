<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David-FortisArt
 * Date: 3/17/13
 * Time: 4:37 PM
 * To change this template use File | Settings | File Templates.
 *
 */?>
<div id="buffer_s_container"><form id="buffers" method="post" action="process/buffering.php"><fieldset><ol>
<input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />
<li><label for="buftype">Type : <em><img src="templates/microfin/imgs/star.gif"/></em></label><select name="buftype" id="buftype" class="required">
<option value="">select particulars</option>
<option value="1">To bank</option>
<option value="2">To head office</option>
<option value="3">To branch</option>
<option value="4">To reserve</option>
<option value="5">From bank</option>
<option value="6">From head office</option>
<option value="7">From branch</option>
<option value="8">From reserve</option>
</select></li>
<li><label for="bufparticulars">Particulars : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" id="bufparticulars" name="bufparticulars" class="required" /></li></li>
<li><label for="bufamount">Amount : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="bufamount" id="bufamount" class="required" /></li>
</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="bufsubmit" id="bufsubmit" /></fieldset></form></div>