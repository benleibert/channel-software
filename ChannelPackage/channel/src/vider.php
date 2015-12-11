<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

if($_SESSION['GL_USER']['DROIT']['data_vid']['VISIBLE']!=1) header("location:accessinterdit.php?selectedTab=home");

//Left Menu
$leftMenu = basededonneesMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);
$msg  ='';
if(isset($_GET['rs']) && $_GET['rs']==1) { //Ajout
	$text = "Les données ont été supprimées avec succès";
	$msg = '<div class="okMsg">'.(stripslashes($text)).'</div>';}
	
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


function validateForm(){
		var msg = confirm("Confirmer la suppression des données?");
		if(msg == true) {
  			document.formadd.submit();
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
</table></td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            
            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?></td>
            <td align="right">&nbsp;
            	</td>
            <td></td>
				
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
            <td width="180"><?php echo $leftMenu; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
          <tr>
            <td width="43"  bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(181); ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>

          <tr>
            <td align="left" valign="top" class="text">
              <p><?php echo getlang(404); ?></p>
              <ul>
                <li><?php echo getlang(36); ?></li>
                <li><?php echo getlang(86); ?></li>
                <li><?php echo getlang(237); ?></li>
                <li><?php echo getlang(111); ?></li>
                <li><?php echo getlang(166); ?></li>
                <li><?php echo getlang(143); ?></li>
                <li><?php echo getlang(73); ?></li>
                <li><?php echo getlang(336); ?></li>
                <li><?php echo getlang(88); ?></li>
                </ul>
              <p><br />
                  <p>&nbsp;</p>
                <form action="dbbackup.php?do=vider" method="post" name="formadd" id="formadd">
                <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                  <tr class="header2Bg">
                   
                  </tr>
                  <tr>
                    <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                      <tbody>
                        <tr align="left" valign="top">
                          <td><input name="Vider" type="button" class="button" id="Vider"  value="<?php echo getlang(218); ?>" onclick="validateForm();" />
                            <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(217); ?>' /></td>
                          </tr>
                      </tbody>
                    </table></td>
                  </tr>
                </table>
          </form>
              <p><?php echo $msg; ?></p></td>
          </tr>
          <!-- Data Tbale contener -->
        </table></td>
      </tr>
    </table><br>


</td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
