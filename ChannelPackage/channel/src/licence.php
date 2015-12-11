<?php
session_start();
require_once('../src/topmenus.php');				//The menubar
require_once('../lib/phpfuncLib.php');		//All commun functions

if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?myaction=logout");
$listeExercice= listeExercice($_SESSION['GL_USER']['EXERCICE']); //Liste des exercices budgétaires
$droitTOPMENUS = getDroitTOPMENUS( $_SESSION['GL_USER']['GROUPE']);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<META HTTP-EQUIV="refresh" CONTENT="" >
<title>Gest-Stocks 1.0</title>
<LINK REL="SHORTCUT ICON" HREF="../images/favicon0.ico">
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css">
<link href="../lib/JQuerySpinBtn.css" rel="stylesheet" type="text/css">
<link href="../lib/jquery.alerts.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
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
<body class="bodyBg">
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg">
	<script language="JavaScript" type="text/JavaScript">
	<!--
	function clearText(){
		document.searchForm.searchTerm.value="";
	}
	function validateValues(){
		if(this.document.searchForm.searchTerm.value == ''){
			alert("Please enter the device name to search")
			 return false;
		}
		return true;
	}
	
	//-->
	</script>

<!-- End of JS code  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="200" rowspan="2"><img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 /></td>
    <td height="24" colspan="2" align="right" valign="top"><span class="wtext"><?php echo RIGHT_MENU; ?></td>
  </tr>
  <tr>
    <td height="20" valign="top"><?php echo $menu['Top']; ?></td>
    <td align="right"><?php echo LOGOUT; ?>&nbsp;</td>
  </tr>
</table>
    </td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            
            <td align="left" class="leftHeader">
            | <span class="Style2" ><font color="#066">Exercice budg&eacute;taire <?php echo $_SESSION['GL_USER']['EXERCICE']; ?> &gt;&gt;&nbsp;<font color="#066">
            <?php  if($_SESSION['GL_USER']['STATUT_EXERCICE']==1) echo '[Cl&ocirc;tur&eacute;]'; ?>
            </font>&nbsp;</font> </span></td>
            <td align="right">&nbsp;
            	</td>
            <td>
				<input type="hidden" name="selectedLink" value="">
				<input type="hidden" name="selectedTab" value="Network Database"></td>
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
            <td width="180">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(185); ?> <?php echo TITLE; ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>
          <!-- Data Tbale contener -->
        </table></td>
      </tr>
    </table>
      <table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                    <tr>
                      <td height="10" ></td>
                    </tr>
					<tr>
                      <td colspan=2 align="left" valign="top">
                          <p class="text">Logiciel : <strong>Gest-Stocks webappls</strong></p>
                          <p class="text">Conception &amp; r&eacute;alisation : <strong>KG</strong><br>
                          E-mail: <strong>idris.coulibaly@gmail.com</strong> </p>
                          <br>
                          <br>
						  <input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
                      </form></td>
                    </tr>
                </table></td>
              </tr>
          </table></td>
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
