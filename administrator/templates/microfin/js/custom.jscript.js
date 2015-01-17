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
