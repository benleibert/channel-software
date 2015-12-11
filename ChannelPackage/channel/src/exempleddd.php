<?php
session_start();
require_once('../lib/phpfuncLib.php');		//All commun functions
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

//Number of ligne
(isset($_GET['lg']) ? $lg = $_GET['lg']: $lg ='');

//Next link
$urlAlph = "listeproduit.php?lg=$lg&wh=";

//JS code to send data in form
function jscode($lg) {
	$code ='';
	if($lg != '') {
		$code .= '<script language="JavaScript"> 
					function pickUp(idArticle,designat,unite,prixUnit){
						window.opener.document.formadd.codeproduit'.$lg.'.value = idArticle;
						window.opener.document.formadd.produit'.$lg.'.value = designat;
						window.opener.document.formadd.prix'.$lg.'.value = prixUnit;
						//window.opener.document.formadd.qteDispo'.$lg.'.value = qteDispo;
						window.opener.document.formadd.unite'.$lg.'.value = unite;
						//window.opener.document.formadd.mntTotal'.$lg.'.value = prixUnit;
						
						//window.opener.document.formadd.qte'.$lg.'.focus;
						//if(window.opener.document.formadd.qte'.$lg.'.value =="") {window.opener.document.formadd.qte'.$lg.'.value = 1;}
						window.close();
					}
					</script>';
	}
	return $code;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="refresh" CONTENT="" >
<title><?php echo TITLE; ?></title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

<!-- Begin of JS code  -->
<?php echo jscode($lg); ?>
<script>
window.focus();
</script>

<script>
//Duplicated in MainLayout.jsp
function writeTableStartTagBasedOnResolution()
{
	var winW = 1024;
	if (parseInt(navigator.appVersion)>3) 
	{ 
		if (navigator.appName=="Netscape") 
		{ 
			winW = window.innerWidth; 
		} 
		if (navigator.appName.indexOf("Microsoft")!=-1) 
		{ 
			winW = document.body.offsetWidth; 
		} 
	}
	if(winW < '1024')
	{
		document.write("<table width=1024  border=0 cellspacing=0 cellpadding=0>");
	}
	else
	{
		document.write("<table id=maintable width=100%  border=0 cellspacing=0 cellpadding=0>");
	}
}
</script>
<style type="text/css">
<!--
.Style2 {
	font-size: x-large;
	font-family: "Times New Roman", Times, serif;
}
-->
</style></head>
<body class="bodyBg2" >
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg"><script language="JavaScript" type="text/JavaScript">
</script>

<!-- End of JS code  -->
<table width="350" border="0" cellpadding="0" cellspacing="0">
  <tr> 
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
        <td height="24" colspan="2" align="right" valign=top>&nbsp;</td>
      </tr>
          <tr> 
            <td height="20" valign="top">
            <td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
           <span class="wtext">	  </span></td>
        </tr>
      </table>
    </td>
</tr>
    
    <tr class="searchBg">
      <td height="21" align="right">&nbsp;	 </td>
    </tr>
    <tr class=bodyBg2>
      <td height="8"></td>
    </tr>
  </table>
</td>
  </tr>
</table>
<script> writeTableStartTagBasedOnResolution(); </script>
  		 <tr>
		 <td width="200" valign="top">
         </td>
		 <td width="10"></td>    
    <td width="*%" valign="top">


    </td>
  		 </tr>
  		 <table width="400"   border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="400" align="left" valign="top">
    <table width="400" border="0" align="left" cellpadding="1" cellspacing="1">
      <tr>
        <td height="20" colspan="2" class="leftHeader"><?php echo getlang(321); ?></td>
      </tr>
      <tr>
        <td colspan=2 align="left" valign="top" height="3"></td>
      </tr>
      <tr>
        <td nowrap>&nbsp;</td>
        </tr>
      <!-- Data Tbale contener -->
      <tr>
        <td colspan=2 align="left" valign="top">dgdgd</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
