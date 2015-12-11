<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="refresh" CONTENT="" >
<title>Gest-Stocks 1.0</title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<link href="../css/defaultcss.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
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
</style>
</head>
<body class="bodyBg">
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg"><script language="JavaScript" type="text/JavaScript">
<!--
function clearText()
{
	document.stocksform.searchTerm.value="";
}
function validateValues()
{
	if(this.document.TabSearchForm.searchTerm.value == '')
	{
		alert("Please enter the device name to search")
			return false;
	}
	return true;
}

//-->
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function doPersonalize()
{
	  var str = window.location.href;
	  re = /&/gi;
	  newstr=str.replace(re, "*");
	  MM_openBrWindow('/webclient/common/jsp/skinSelection.jsp?linkUrl='+newstr,'skins','left=300,top=150,width=500,height=450')
}

//-->
</script>
<!-- End of JS code  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
          </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext">
          <a href="http://www.econsulting.bf/forums/index.php?id=STOCKS" target="_blank"><img src="../images/forums00.gif" border="0" hspace="3" align="middle" />Forums</a> <span class=white> </span>
          <a href="" onClick=""><img src="../images/talkback.gif" border="0" hspace="3" align="middle" />R&eacute;agir</a><span class=white> </span>
          <a href="" onClick="javascript:window.open('perso.php','Personnalisation','left=500,top=100,width=550,height=250')"><img src="../images/personal.gif" border="0" hspace="3" align="middle" />Personnaliser</a>          <span class=white> </span>
          <a href="" onClick="javascript:window.open('aide/index.html','','')"><img src="../images/help0000.gif" border="0" hspace="3" align="middle" />Aide</a> <span class=white> </span>
          <a href="" onClick="JavaScript:window.open('/about.php','A propos','left=500,top=100,width=350,height=300')" ><img src="../images/about000.gif" border="0" hspace="3" align="middle" />A propos</a>&nbsp;&nbsp;
	  </td>
      </tr>
          <tr>
            <td height="20" valign="top">
            <table cellpadding=0 cellspacing=0 border=0 id="tab">
              <tr>
                <td nowrap class="menuOffBg"><a id="homelink" href="../src/index.php?selectedTab=home&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff">Accueil</a>
                </td>
                <td nowrap class="menuOffBg"> <a href="../src/needs.php?selectedTab=needs&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff">Besoins</a>
                </td>
                 <td nowrap class="menuOffBg" ><a href="../src/demands.php?selectedTab=commands&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff">Appels d'offre</a>
                </td>
				<td nowrap class="menuOffBg" ><a href="../src/receptions.php?selectedTab=receptions&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff">R&eacute;ceptions</a>
                </td>
				<td nowrap class="menuOffBg" ><a href="../src/outings.php?selectedTab=outings&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff"><?php echo getlang(17); ?></a>
                </td>
				<td nowrap class="menuOffBg" ><a href="../src/sends.php?selectedTab=sends&displayName=&viewId=&selectedOption=&selectedLink=" class="menuOff"><?php echo getlang(86); ?></a>
                </td>
				<td nowrap class="menuOffBg" ><a href="../src/parameters.php?selectedTab=pareters&displayName=parameters&viewId=&selectedOption=parameters&selectedLink=" class="menuOff">Paramétrage</a>
				</td>
		 		<td nowrap class="menuOnBg" ><a href="../src/support.php" class="menuOff">Support</a>
                </td>
              </tr>
            </table><td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
           <span class="wtext">
	  <a href="../src/licence.php?selectedTab=home" >License</a>
	   <span class=white> &nbsp;| &nbsp;</span>
	   <?php echo '<a href="phpfuncindex.php?myaction=LOGOUT" title="'.$_SESSION['GL_USER']['NOM'].'">'.$_SESSION['GL_USER']['LOGIN'].' [Déconnexion]</a>'; ?></span>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>

    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            <form name="stockform" action="file:///C|/wamp/www/sources/search.php" method="post" onsubmit=''>
            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?></td>
            <td align="right">&nbsp;

              </td>
            <td><input name="Go" type="submit" class="buttonGo" value="GO"></td>
            	<input type="hidden" name="requestid" value="SNAPSHOT">
				<input type="hidden" name="selectedLink" value="">
				<input type="hidden" name="selectedTab" value="Network Database">
	    <td>&nbsp;</td>
	    </form>
	  </tr>
	</table>

	 </td>
    </tr>
    <tr class=bodyBg>
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
    <td width="*%" valign="top"><script>
window.focus();
</script>
<script>
function openWindow(theURL,winName,width,height,parms)
{
    var left = Math.floor( (screen.width - width) / 2);
    var top = Math.floor( (screen.height - height) / 2);
    var winParms = "top=" + top + ",left=" + left + ",height=" + height + ",width=" + width +",scrollbars=yes";
    if (parms) { winParms += "," + parms; }
    window.open(theURL, winName, winParms);
}
</script>
    </td>
  		 </tr>
</table>
<table width="100%" height="80%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="80%" align="left" valign="top"><table width="200" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5" height="48">&nbsp;</td>
        <td width="180" align="left" valign="top"><table width="200" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td width="180"><?php
			(isset($_GET['selectedTab']) ?  $selectedTab = $_GET['selectedTab'] : $selectedTab = '');
			include '../src/menus.php';
			?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
