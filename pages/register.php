<div class="grid">
    <div id="registration_container"><form  id="registerclient" method="post" action="process/register.php"><fieldset><ol>
        <li><label for="acno">A.C number: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="hidden" name="accno" id="accno" /> <span id="acno"></span></li>
        <li><label for="name">Name: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="name" id="name" class="required" /></li>
        <li><label for="age">Age: </label><input type="text" name="age" id="age" /></li>
        <li><label for="sex">Gender: <em><img src="templates/microfin/imgs/star.gif"/></em></label><select name="sex" id="sex" class="required"><option value="">Select gender</option>]
         <option value="Female">Female</option><option value="Male">Male</option></select></li>
        <li><label for="idno">ID No: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="idno" id="idno" class="required"/></li>
        <li><label for="idno">Computer No: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="compno" id="compno" class="required"/></li>
        <li><label for="phno">Phone number: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="phno" id="phno" class="text" /></li>
        <li><label for="addr">Physical address:</label><textarea name="addr" id="addr"></textarea></li>
        </ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="submit" id="submit" /></fieldset></form></div>
</div>
<script type="text/javascript">
$('document').ready(function(){
var x = new Date();
var secs	=  x.getTime();
$.get("process/process.php?wat=acn&x"+ secs, function(data){
$('#acno').css({'color':'#f00'});
$('#accno').val(data);
$('#acno').text(data+" system generated");
});
});
</script>