<?php
session_start();
//if($_SESSION['GL_USER']['IDSESSION'] != session_id())header("location:dbuser.php?do=logout");

//PHP functions librairy
require_once('../lib/phpfuncLib.php');	//All commun functions
require_once('menus.php');			//Menu functions
//require_once('funcaction.php');		//Profil functions

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = homeMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);
//Number of ligne
(isset($_GET['lg']) ? $lg = $_GET['lg']: $lg ='');

//Next link
$urlAlph = "etatdustockproduit.php?selectedTab=home&lg=$lg&wh=";

//JS code to send data in form
function jscode($lg) {
	$code ='';
	if($lg != '') {
		$code .= '<script language="JavaScript"> 
					function pickUp(idArticle,designat,unite,qteDispo,prixUnit,refLot,Datep,MonLot){
						window.opener.document.formadd.codeproduit'.$lg.'.value = idArticle;
						window.opener.document.formadd.produit'.$lg.'.value = designat;
						window.opener.document.formadd.prix'.$lg.'.value = prixUnit;
						window.opener.document.formadd.dispo'.$lg.'.value = qteDispo;
						window.opener.document.formadd.unite'.$lg.'.value = unite;
						window.opener.document.formadd.reflot'.$lg.'.value = refLot;
						window.opener.document.formadd.dateperemp'.$lg.'.value = Datep;
						window.opener.document.formadd.monlot'.$lg.'.value = MonLot;
						//window.opener.document.formadd.mntTotal'.$lg.'.value = prixUnit;
						
						//window.opener.document.formadd.qte'.$lg.'.focus;
						//if(window.opener.document.formadd.qte'.$lg.'.value =="") {window.opener.document.formadd.qte'.$lg.'.value = 1;}
						window.close();
					}
					</script>';
	}
	return $code;
}

$whereAll="  mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND mouvement.ID_EXERCICE='".$_SESSION['GL_USER']['EXERCICE']."' ";

(isset($_GET['wh']) ? $wh=$_GET['wh'] : $wh = '');
$listeDesProduits =  listeDesProduitsStockparproduit($wh,$whereAll);
			

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
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>
<script type="text/javascript">
function validateForm(){
        var exercice = trimAll(document.HomeForm.exercice.options[document.HomeForm.exercice.selectedIndex].value);
		var province = trimAll(document.HomeForm.province.options[document.HomeForm.province.selectedIndex].value);
		var msg = '';
		
		if(exercice == "00") {
        	msg += '- Veuillez sélectionner l\'exercice budgétaire.\n';
        }
		if(province == "00") {
        	msg += '- Veuillez sélectionner une province.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	//document.HomeForm.myaction.value='change';
			document.HomeForm.submit();
        }
}

function validateForm2(){
        var exercice = trimAll(document.HomeForm.exercice.options[document.HomeForm.exercice.selectedIndex].value);
		var cantine = trimAll(document.HomeForm.cantine.options[document.HomeForm.cantine.selectedIndex].value);
		var province = trimAll(document.HomeForm.province.options[document.HomeForm.province.selectedIndex].value);
		var msg = '';
		
		if(exercice == "00") {
        	msg += '- Veuillez sélectionner l\'exercice budgétaire.\n';
        }
		if(cantine == "00") {
        	msg += '- Veuillez sélectionner une cantine.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	//document.HomeForm.myaction.value='change';
			document.HomeForm.submit();
        }
}

function fillService(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('souscategorie').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbetat.php?do=fillSousCat",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('categorie').options[document.getElementById('categorie').selectedIndex].value;
	xhr.send("categorie="+id);
}

</script>

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
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext"><?php echo RIGHT_MENU; ?>
	  </td>
      </tr>
          <tr> 
            <td height="20" valign="top"><?php echo $menu['Top']; ?>
            <td align="right"> <?php echo LOGOUT; ?>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>
            
            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?> </td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
				<td>&nbsp;</td>
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
            <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(57); ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>
          <!-- Data Tbale contener -->
        </table></td>
      </tr>
    </table>
      <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
        <tr>
          <td colspan="3" align="left" valign="top" height="3"></td>
        </tr>
        <tr>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap"><input name="A" type="button" class="button" id="A" onclick="javascript:window.location.href='<?php echo $urlAlph.'A';?>';" value="A" />
            <input name="B" type="button" class="button" id="B" onclick="javascript:window.location.href='<?php echo $urlAlph.'B';?>';" value="B" />
            <input name="C" type="button" class="button" id="C" onclick="javascript:window.location.href='<?php echo $urlAlph.'C';?>';" value="C" />
            <input name="D" type="button" class="button" id="D" onclick="javascript:window.location.href='<?php echo $urlAlph.'D';?>';" value="D" />
            <input name="E" type="button" class="button" id="E" onclick="javascript:window.location.href='<?php echo $urlAlph.'E';?>';" value="E" />
            <input name="F" type="button" class="button" id="F" onclick="javascript:window.location.href='<?php echo $urlAlph.'F';?>';" value="F" />
            <input name="G" type="button" class="button" id="G" onclick="javascript:window.location.href='<?php echo $urlAlph.'G';?>';" value="G" />
            <input name="H" type="button" class="button" id="H" onclick="javascript:window.location.href='<?php echo $urlAlph.'H';?>';" value="H" />
            <input name="I" type="button" class="button" id="I" onclick="javascript:window.location.href='<?php echo $urlAlph.'I';?>';" value="I" />
            <input name="J" type="button" class="button" id="J" onclick="javascript:window.location.href='<?php echo $urlAlph.'J';?>';" value="J" />
            <input name="K" type="button" class="button" id="K" onclick="javascript:window.location.href='<?php echo $urlAlph.'K';?>';" value="K" />
            <input name="L" type="button" class="button" id="L" onclick="javascript:window.location.href='<?php echo $urlAlph.'L';?>';" value="L" />
            <input name="M" type="button" class="button" id="M" onclick="javascript:window.location.href='<?php echo $urlAlph.'M';?>';" value="M" />
            <input name="N" type="button" class="button" id="N" onclick="javascript:window.location.href='<?php echo $urlAlph.'N';?>';" value="N" />
            <input name="O" type="button" class="button" id="O" onclick="javascript:window.location.href='<?php echo $urlAlph.'O';?>';" value="O" />
            <input name="P" type="button" class="button" id="P" onclick="javascript:window.location.href='<?php echo $urlAlph.'P';?>';" value="P" />
            <input name="Q" type="button" class="button" id="Q" onclick="javascript:window.location.href='<?php echo $urlAlph.'Q';?>';" value="Q" />
            <input name="R" type="button" class="button" id="R" onclick="javascript:window.location.href='<?php echo $urlAlph.'R';?>';" value="R" />
            <input name="S" type="button" class="button" id="S" onclick="javascript:window.location.href='<?php echo $urlAlph.'S';?>';" value="S" />
            <input name="T" type="button" class="button" id="T" onclick="javascript:window.location.href='<?php echo $urlAlph.'T';?>';" value="T" />
            <input name="U" type="button" class="button" id="U" onclick="javascript:window.location.href='<?php echo $urlAlph.'U';?>';" value="U" />
            <input name="V" type="button" class="button" id="V" onclick="javascript:window.location.href='<?php echo $urlAlph.'V';?>';" value="V" />
            <input name="W" type="button" class="button" id="W" onclick="javascript:window.location.href='<?php echo $urlAlph.'W';?>';" value="W" />
            <input name="X" type="button" class="button" id="X" onclick="javascript:window.location.href='<?php echo $urlAlph.'X';?>';" value="X" />
            <input name="Y" type="button" class="button" id="Y" onclick="javascript:window.location.href='<?php echo $urlAlph.'Y';?>';" value="Y" />
            <input name="Z" type="button" class="button" id="Z" onclick="javascript:window.location.href='<?php echo $urlAlph.'Z';?>';" value="Z" />
            <input name="Tous" type="button" class="button" id="Tous" onclick="javascript:window.location.href='<?php echo $urlAlph;?>';" value="Tous" /></td>
        </tr>
        <!-- Data Tbale contener -->
        <tr>
          <td colspan="3" align="left" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="1" class="tableBorder">
            <!-- Begin Table Header -->
            <tr class="header2">
              <td width="2%" align="left" valign="middle" class="header2Bg">N°</td>
              <td width="10%" height="25" align="left" valign="middle" class="header2Bg">Code</td>
              <td width="40%" height="25" align="left" valign="middle" class="header2Bg">&nbsp;Libell&eacute; produit</td>
              <td width="15%" align="center" valign="middle" class="header2Bg">Qté</td>
              <td width="15%" align="center" valign="middle" class="header2Bg">Prix</td>
              
              <td width="34" height="25" align="center" valign="middle" class="header2Bg" nowrap="nowrap"><?php echo getlang(204); ?></td>
            </tr>
            <?php 
			echo $listeDesProduits;
			?>
            <!-- End Table row -->
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
