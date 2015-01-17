$('document').ready(function(){
setInterval(
function(){
	$('#op').load("process/process.php?wat=csh&ival="+Math.random(),
	function(data){
	$(this).css("background","white"); //IE fadeIn and fadeOut fix
	(($(this).text()<=500000))?$(this).css('color','red'):$(this).css('color','blue');
	$(this).fadeIn(1000);
	$(this).fadeOut(1000);
	$(this).text(formatNumber(data,2,',','.','UGX ','','',' CR'));
})},2000);
$('#rs').load("process/process.php?wat=rsv&ival="+Math.random(),
	function(data){$(this).text(formatNumber(data,2,',','.','UGX ','','',' CR'));});
 //design table rows
$('table#list tr:first').addClass('first');
$('table#list tr:odd').addClass('odd');
$('table#list tr:even').addClass('even');
$('table#list tr:last').addClass('last');
//Manipulate tabs on client page
$(".wide").hide(); // Hide all tab conten divs by default
		$(".wide:first,.sammary:first").show(); // Show the first div of tab content by default
		$("ul.tabs li a").click(function(){ //Fire the click event
			var activeTab = $(this).attr("href"); // Catch the click link
			$("ul.tabs li a").removeClass("active"); // Remove pre-highlighted link
			$(this).addClass("active"); // set clicked link to highlight state
			$(".wide").hide(); // hide currently visible tab content div
			$(activeTab).show(); //slideDown();  show the target tab content div by matching clicked link.
		});
    $('.loans a').click(function(){
        $('.loans').toggle();
    });
    if($('.custombutton').length>0){
        $('.loans').hide();
        $('.custombutton:eq(1)').click(function(){
            $('.warning').hide();
            $('.loans:eq(0)').show();
        });
    }else{
        $('.loans').hide();
        $('.loans:eq(0)').show();
    }
  //manipulat content summary in right column of client page
   $('.sammary:last').css('border','none');

    });
function formatNumber(num,dec,thou,pnt,curr1,curr2,n1,n2){
 var x = Math.round(num * Math.pow(10,dec));
	 if (x >= 0) n1=n2='';
	 var y = (''+Math.abs(x)).split('');
	 var z = y.length - dec;
	 if (z<0) z--;
	 for(var i = z; i < 0; i++) y.unshift('0'); if (z<0) z = 1; 
	 y.splice(z, 0, pnt); 
	 if(y[0] == pnt) y.unshift('0');
	 while (z > 3) {
			z-=3;
	 y.splice(z,0,thou);
	 }
	 var r = curr1+n1+y.join('')+n2+curr2;
	 return r;
}