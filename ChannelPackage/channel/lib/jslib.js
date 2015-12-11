function openPage(str){
	window.location.href=str
}

function openListe(xurl, xtitre, xleft, xtop, xscrollbars, xwidth, xheight){
	var myOption = '';
	myOption += 'left=' + xleft + ',';
	myOption += 'top=' + xtop + ',';
	myOption += 'scrollbars=' + xscrollbars + ',';
	myOption += 'width=' + xwidth + ',';
	myOption += 'height=' + xheight + ',';
	
	window.open(xurl,xtitre,"'"+myOption+"'");
}