<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['int_int']['AJOUT']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcinventaire.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = inventaireMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['int_int'];
//Set default
(isset($_GET['error']) ? $msg = errorMessage($_GET['error']): $msg = '');	//Message d'error
(isset($_SESSION['DATA_INVENT']['reference'])? $reference = $_SESSION['DATA_INVENT']['reference'] : $reference ='');
(isset($_SESSION['DATA_INVENT']['dateAjout'])? $dateAjout = $_SESSION['DATA_INVENT']['dateAjout'] : $dateAjout ='');
(isset($_SESSION['DATA_INVENT']['libelle'])? $libelle = $_SESSION['DATA_INVENT']['libelle'] : $libelle ='');

//Call lignBesoins($nbre) to deplay lignes
$lignDetInventaire = lignDetInventaire($_SESSION['DATA_INVENT']['ligne']);
session_unregister('DATA_INVENT');

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
<link href="../css/defaultcss.css" rel="stylesheet" type="text/css">
<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

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


//-->
</script>
<!-- End of JS code  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext">
          <?php echo RIGHT_MENU; ?>
	  </td>
      </tr>
          <tr>
            <td height="20" valign="top"><?php
			  $selectedTab = $_GET['selectedTab'];
			  echo topMenus($selectedTab,$droitTOPMENUS);
			  ?>
            <td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
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
            <td width="180"><?php echo $leftMenu; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                <tr>
                  <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(298); ?> - Faire un inventaire</td>
                </tr>
                <tr>
                  <td colspan=2 align="left" valign="top"><form name="FormInventaire" action="../src/phpfuncinventaires.php" method="POST">
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(11); ?> inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                            <tbody>
							<tr align="left" valign="top">
                          <td width="100%" align="left" valign="middle" colspan=2><div class="msgBox"><?php echo $msg; ?></div></td>
                        </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">R&eacute;f&eacute;rence&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $reference;?>
								<input name="reference" type="hidden" class="formStyle" id="reference" value="<?php echo $reference;?>"></td>
                              </tr>
                              <tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text"><?php echo getlang(42); ?> :&nbsp;</td>
                                      <td width="358" align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $dateAjout;?></div>
                                          <span class="mandatory"></span> </td>
                                    </tr>
									<tr align="left" valign="top">
                                      <td width="200" align=right valign="middle" class="text">Libell&eacute;&nbsp;:&nbsp;</td>
                                      <td align="left" class="text"><div class="ligneAll" nowrap="nowrap"><?php echo $libelle;?></div>
                                          <span class="mandatory"></span> </td>
                                    </tr>

                          </table></td>
                        </tr>
                      </table>
                          <br>
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Lignes du inventaire</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="623" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <td width="60" align="center" valign="middle" nowrap class="text"><div align="center">P. unitaire</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center">Bonus/Malus</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?></div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  <td width="82"  valign="middle" class="text"><div align="center">Mnt total</div></td>
                                  </tr>
                           		  <?php echo $lignDetInventaire;  ?>
					          	<tr>
                                <td colspan="8">
								<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
						<input name='myaction' type='hidden' id="myaction" value=''>
						<input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                        <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td width="4">&nbsp;</td>
  								<tr align="left" valign="top">
  								<td width="4">&nbsp;</td>
  								<td colspan="2">
								<input name="Precedent" type="button" class="button" id="Precedent"  value="Ajouter un nouvel inventaire" onClick="javascript:window.location.href='addinventaire.php?selectedTab=inputs';">
								</td>
							  </tr>
                          </table></td>
                        </tr>
                      </table>
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
