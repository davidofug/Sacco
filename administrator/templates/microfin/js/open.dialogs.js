//Beign Registration form
var n2 = (new Date()).getTime();
var reg ='<div id="registration_container"><form  id="registerclient" method="post" action="process/process.php?form=reg"><fieldset><ol>';
reg +='<li><label for="acno">A.C number: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="hidden" name="accno" id="accno" /> <span id="acno"></span></li>';
reg +='<li><label for="name">Name: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="name" id="name" class="required" /></li>';
reg +='<li><label for="age">Age: </label><input type="text" name="age" id="age" /></li>';
reg +='<li><label for="sex">Gender: <em><img src="templates/microfin/imgs/star.gif"/></em></label><select name="sex" id="sex" class="required"><option value="">Select gender</option><option value="Female">Female</option><option value="Male">Male</option></select></li>';
reg +='<li><label for="idno">ID No: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="idno" id="idno" class="required"/></li>';
reg +='<li><label for="phno">Phone number: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="phno" id="phno" class="text" /></li>';
reg +='<li><label for="addr">Physical address:</label><textarea name="addr" id="addr"></textarea></li>';
reg +='</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="submit" id="submit" /></fieldset></form></div>';
//Begin buffer's form
var buf='<div id="buffer_s_container"><form id="buffers" method="post" action="process/process.php?form=buf"><fieldset><ol>';
buf +='<input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />';
buf +='<li><label for="buftype">Type : <em><img src="templates/microfin/imgs/star.gif"/></em></label><select name="buftype" id="buftype" class="required">';
buf +='<option value="">select particulars</option><option value="1">To bank</option><option value="2">To head office</option><option value="3">To branch</option><option value="4">From bank</option>';
buf +='<option value="5">From head office</option><option value="6">From branch</option></select></li>';
buf +='<li><label for="bufparticulars">Particulars : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" id="bufparticulars" name="bufparticulars" class="required" /></li></li>';
buf +='<li><label for="bufamount">Amount : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="bufamount" id="bufamount" class="required" /></li>';
buf +='</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="bufsubmit" id="bufsubmit" /></fieldset></form></div>';
//Begin collect refund form
var ref ='<div id="refund_s_container"><form id="refunds" method="post" action="process/process.php?form=ref"><fieldset><ol>';
ref +='<input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />';
ref +='<li><label for="refaccount">Enter account number: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="refaccount" id="refaccount" class="required" /></li>';
ref +='<li><label for="refamount">Enter amount: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="refamount" id="refamount" class="required" /></li>';
ref +='</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="refsubmit" id="refsubmit" /></fieldset>';
//Begin expenses form
var exp='<div id="expense_s_container"><form id="expenses" method="post" action="process/process.php?form=exp"><fieldset><ol>';
exp +='<input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />';
exp +='<li><label for="exptype">Type : <em><img src="templates/microfin/imgs/star.gif"/></em> </label><select name="exptype" id="exptype" class="required">';
exp +='<option value="">Select expense</option><option value="airtime">Artime</option><option value="transport">Transport</option>';
exp +='<option value="fuel">Fuel</option><option value="accomodation">Accomodation</option><option value="rent">Rent</option><option value="office">Office</option>';
exp +='<option value="general">General</option><option value="salaries">Salaries</option><option value="wages">Wages</option><option value="electricity">Electricity</option>';
exp +='<option value="water">Water</option><option value="stationery">Satationery</option><option value="legal">Legal</option><option value="public relations">Public relations</option>';
exp +='<option value="bank charges">Bank charges</option><option value="repeir & maintanence">Repair & Maintanence</option><option value="others">Others</option></select></li>';
exp +='<li><label for="expparticulars">Particulars : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="expparticulars" id="expparticulars" class="required" /></li>';
exp +='<li><label for="expamount">Amount : <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="expamount" id="expamount" class="required" /></li>';
exp +='</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="expsubmit" id="expsubmit" /></fieldset>';
//Begin loan form
var loan='<div id="loan_s_container"><form id="loan" method="post" action="process/process.php?form=loan"><fieldset><ol>';
loan+='<input type="hidden" name="formid" id="formid" value="'+ (new Date()).getTime() +'" />';
loan+='<p style="color:blue">You\'re about to give a loan to, <span style="color: orange" id="displayname"></span>.</p><br/>';
loan+='<li><label for="accnum">Borrower a/c no. :<em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="hidden" name="accnum" id="accnum" /><input type="hidden" name="client" id="client" /><b><span style="color:blue" id="acnum"></span></b></li>';
loan+='<li><label for="disbamount">Disb amount: <em><img src="templates/microfin/imgs/star.gif"/></em></label><input type="text" name="disbamount" id="disbamount" class="required"/></li>';
loan+='</ol></fieldset><fieldset class="submit"><input type="submit" value="submit" name="loansubmit" id="loansubmit" /></fieldset>';
$(function(){
//Start registration execution
$(".register").colorbox({width:"550px",height:"450px",html:reg},
function(){
var x = new Date();
var secs	=  x.getTime();
$.get("process/process.php?wat=acn&x"+ secs, function(data){
	$('#acno').css({'color':'#f00'});
	$('#accno').val(data);
	$('#acno').text(data+" system generated");
});
 $('#registerclient').validate({
		rules:{
			age:{
					digits:true,
					maxlength:3,
					max:130,
					min:18
				},
			phno:{
					required:true,
					maxlength:15,
					minlength:10,
					digits:true
					}
			},
		messages:{
			name:{
					required:"Enter client's name."
					},
			age:{
				digits: "Enter age in digits (0-9).",
				maxlength: "Enter reasonable age.",
				max: "Enter correct age.",
				min: "Enter correct age, client should be 18+ years"
				},
			sex:{
				required:"Specify gender(sex)"
				},
			idno:{
				required: "Enter ID number"
				},
			phno:{
				required: "Enter phone number",
				digits:	"Enter correct phone number eg 07xxxxxxxx",
				maxlength: "Phone number should not exceed 15 character limit",
				minlength: "Enter correct phone number"
				}
			},
		errorElement:'span',
		success: function(span){
							// set &nbsp; as text for IE
							span.html("&nbsp;").addClass("checked");
						},					
		submitHandler: function(form){
							$(form).ajaxSubmit({
							success: function(){
								$('#registerclient').fadeOut(500);
								setTimeout($.fn.colorbox.close,3000);
								},
							target: '#registration_container'
				});		}
		});
});//End registration execution
//Start buffers exection
$(".buffers").colorbox({width:"500px",height:"300px",html:buf},function(){
$("#buffers").validate({
	rules:{
		bufamount:{
			digits:true,
			maxlength:10,
			minlength:3
			}
		},
	messages:{
		buftype:{
			required:'Please select the type of the buffer!'
				},
	bufparticulars:{
			required: 'Please enter the buffer particular!'
				},
	bufamount:{
		required: 'Enter amount for buffer',
		digits:	'Amount should be in digits e.g 100000',
		remote: 'Outing buffer can not exceed current balance',
		minlength: 'Buffer amount is too little',
		maxlength: 'Revise buffer amount entered'
			}
			},
	errorElement:'span',
	success: function(span){
			// set &nbsp; as text for IE
			span.html("&nbsp;").addClass("checked");
		},
		submitHandler: function(form){
							$(form).ajaxSubmit({
							success: function(response){
								
								$('#buffers').fadeOut(500);
								//setTimeout($.fn.colorbox.close,3000);
								},
							target: '#buffer_s_container'
				});		}
	
	});
});//End buffers execution
//Start refund execution
$(".refunds").colorbox({width:"500px",height:"300px",html:ref},function(){
$("#refunds").validate({
	rules:{
		refaccount:{
				digits:true,
				maxlength:10,
				minlength:3
		},
		refamount:{
				digits:true,
				maxlength: 10,
				minlength:3
		}
	},
	messages:{ // The message to be displayed in voilation of rule
		refaccount:{
			required:'Specify account please!',
			digits:'Valid accounts are digits only!',
			maxlength:'Account numbers can be between 3-10 characeters!',
			minlength:'Account numbers can be between 3-10 characeters!'
			},
		refamount:{
			required:'Specify amount please!',
			digits:'Valid amount must be digits!',
			maxlength:'Refund amount can be between 3-10 characeters!',
			minlength:'Refund amount can be between 3-10 characeters!'
			}
	},
	errorElement:'span',
	success:function(span){
		span.html("&nbsp;").addClass("checked");
		},
	submitHandler:function(form){
		$(form).ajaxSubmit({
			success: function(response){
				$('#refunds').fadeOut(500);
				//setTimeout($.fn.colorbox.close,3000);
				},
	target: '#refund_s_container'
				});		
	}
});
});//End refund execution
//Start Expense execution
$(".expenses").colorbox({width:"500px",height:"300px",html:exp},function(){
$("#expenses").validate({
	rules:{
		expamount:{
			digits:true,
			maxlength:10,
			minlength:3
			}
	},
	messages:{ // The message to be displayed in voilation of rule
		exptype:{
			required:'Select expense type'
		},
		expparticulars:{
			required:'Enter expense particulars'
		},
		expamount:{
			required:'Enter expense amount',
			digits:'Only digits(0-9) allowed',
			maxlength:'Expense amount exceeds expected',
			minlength:'Provide appropriate expense amounts'
		}		
	},
	errorElement:'span',
	success:function(span){
	span.html("&nbsp;").addClass("checked");
	},
	submitHandler:function(form){
		$(form).ajaxSubmit({
			success: function(response){
				$('#expenses').fadeOut(500);
				//setTimeout($.fn.colorbox.close,3000);
				},
	target: '#expense_s_container'
				});		
	}
});
});//End Expense excutions
//Start loan executions
$(".loan").live('click',function(e){
	e.preventDefault();
	$(this).colorbox({open:true,width:"500px",height:"300px",html:loan},function(){
var name	=	$(this).parent('td').siblings('td:first').text();
var accnum	=	$(this).parent('td').siblings('td:first').next().text();
$('#loan #accnum').val(accnum);
$('#loan #client').val(name);
$('#loan #displayname').text(name);
$('#loan #acnum').text(accnum);
$('#loan').validate({
	rules:{
		disbamount:{
			digits:true,
			minlength:3,
			maxlength:10
			}
	},
	messages:{
		disbamount:{
			required:'Disbursement amount required!',
			digits:'Disbusement amount digits must digits only',
			minlength:'Amount too less for a loan!',
			maxlength:'Amount too high for a laon!'
			}
	},
	errorElement:'span',
	success:function(span){
	span.html("&nbsp;").addClass("checked");
	},
	submitHandler:function(form){
		$(form).ajaxSubmit({
			success: function(response){
				$('#loan').fadeOut(500);
				//setTimeout($.fn.colorbox.close,3000);
				},
	target: '#loan_s_container'
				});		
	}
	});
	});
});//End loan executions

//Start recover executions
$(".recover").live('click',function(e){
	e.preventDefault();
$(this).colorbox({open:true},function(){
$('#recover').validate({
	rules:{
		amount:{
			digits:true,
			minlength:3,
			maxlength:10
			}
	},
	messages:{
		amount:{
			required:'Recovery amount required!',
			digits:'Recovery amount digits must digits only',
			minlength:'Amount too less for a recovery!',
			maxlength:'Amount too high for a recovery!'
			}
	},
	errorElement:'span',
	success:function(span){
	span.html("&nbsp;").addClass("checked");
	},
	submitHandler:function(form){
		$(form).ajaxSubmit({
			success: function(response){
				$('#recover').fadeOut(500);
				//setTimeout($.fn.colorbox.close,3000);
				},
	target: '#recover_s_container'
				});		
	}
	});
	});
});//End recover executions

//Start refund executions
$(".refund").live('click',function(e){
	e.preventDefault();
	$(this).colorbox({open:true},function(){
$('#refunding').validate({
	rules:{
		disbamount:{
			digits:true,
			minlength:3,
			maxlength:10
			}
	},
	messages:{
	},
	errorElement:'span',
	success:function(span){
	span.html("&nbsp;").addClass("checked");
	},
	submitHandler:function(form){
		$(form).ajaxSubmit({
			success: function(response){
				$('#refunding').fadeOut(500);
				//setTimeout($.fn.colorbox.close,3000);
				},
	target: '#refunding_s_container'
				});		
	}
	});
	});
});//End refund executions
});//Close  JQuery document ready
