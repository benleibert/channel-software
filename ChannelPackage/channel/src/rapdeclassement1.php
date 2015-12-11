<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcetat.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = rapportMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['int_sto'];

if(isset($_SESSION['GL_USER']['MAGASIN']) && $_SESSION['GL_USER']['MAGASIN']==''){
	header('location:home.php?selectedTab=home');
}

//Numuro page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'élément par page
(isset($_POST['viewLength'])  ? $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength']: '');

$affichage = ligneConRapDeclassement($_SESSION['DATA_ETAT']['nbreLigne'],$_SESSION['DATA_ETAT']['ligne']); //$where, $order, $sens, $page=1, $nelt

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

<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

<script langage="javascript">
function go(valeur, ok){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			//document.getElementById('msg').innerHTML = retour;
			if(retour==1 && document.ListingForm.elements[ok].checked==true){
				var rep = confirm('Cette ligne est liée à d\'autres données dans une autre table.\nSi vous la supprimer ou modifier les données liées seront affectées.');
				//alert('Impossible de supprimer cette donnée.\n Veuillez supprimer les provinces associées');
				if(rep == false) document.ListingForm.elements[ok].checked=false;
			}
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfuncprovinces.php?test=BENEFICIAIRE",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	//id = document.getElementById('codeBenef').value;
	xhr.send("codeBenef="+valeur);
}


function doMyAction(myform){
	if(document.ListingForm.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=true;
    	}
	}
	if(document.ListingForm.toggleAll.checked == false){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.ListingForm.elements.length; i++) {
       		document.ListingForm.elements[i].checked=false;
    	}
	}
    return false;
}

function msgModif(){
	var ret;
	var j=0;
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				j++;
			}
    }
	if(j>0){
		if(j==1){
			document.ListingForm.myaction.value="EDIT";
			document.ListingForm.submit();
		}
		else  { ret = confirm('Vous ne pouvez modifier qu\'une seule donnée à la fois.');}

	}
	else alert('Aucun élément sélectionné');
}

function msgSuppress(){
	var ret;
	var j=0;
	for (i = 0; i < document.ListingForm.elements.length; i++) {
       		if(document.ListingForm.elements[i].checked==true){
				j++;
			}
    }
	if(j>0){
		if(j==1){ ret = confirm('Voulez-vous supprimer cette donnée?');}
		else    { ret = confirm('Voulez-vous supprimer ces données?');}
		if(ret==true) {
			document.ListingForm.myaction.value="DEL";
			document.ListingForm.submit();
		}
	}
	else alert('Aucun élément sélectionné');
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
</table>
    </td>
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
            <td width="43"  bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(137); ?> -><?php echo getlang(132); ?> : <?php echo $_SESSION['DATA_ETAT']['choix']; ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>

          <tr>
            <td align="left" valign="top" class="text">
            <!--  Debut affichage -->
            <table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">

    
            <tr>
              <td width="43%">&nbsp;</td>
              <td nowrap align="right" class="text">&nbsp;</td>
              </tr>
            <!-- Data Tbale contener -->
            <tr>
              <td colspan=3 align="left" valign="top">
                <!-- Data Table  -->
                <table width="100%" border="0" cellpadding="2" cellspacing="1" class="tableBorder">
                  <!-- Begin Table Header -->
                  <tr class="header2">
                    <td width="10%" height="25" align="left" valign="middle" class="header2Bg">Code</td>
                    <td width="10%" align="left" valign="middle" class="header2Bg" nowrap="nowrap">Réf. lot</td>
                    <td width="30%" height="25" align="left" valign="middle" class="header2Bg" nowrap="nowrap"><?php echo getlang(199); ?></td>
                    <td width="15%" align="center" valign="middle" nowrap class="header2Bg" ><?php echo getlang(206); ?> <?php echo getlang(111); ?></td>
                    <td width="15%" align="center" valign="middle" nowrap class="header2Bg"><?php echo getlang(262); ?></td>
                    <td width="15%" align="center" valign="middle" nowrap class="header2Bg"><?php echo getlang(235); ?> </td>
                    <td width="15%" align="center" valign="middle" bgcolor="#33CC66" class="header2Bg" nowrap="nowrap"><?php echo getlang(204); ?></td>
                    <td width="15%" height="25" align="center" valign="middle" bgcolor="#33CC66" class="header2Bg" nowrap="nowrap">Montant</td>
                  </tr>
                  <!-- Begin Table row -->
                  <?php echo $affichage;?>
                  <!-- End Table row -->
                  </table>
                <!-- End Data Table --></td>
            </tr>
        </table>
        <tr><td>
         <table border="0" align="right" cellpadding="5" cellspacing="0" class="botBorder">
            <tbody>
              <tr align="left" valign="top">
                <td align="right"><input name="Enregistrer2" type="button" class="button" id="Enregistrer2"  value='<?php echo getlang(187); ?>'  onClick="OpenBigWin('printetatrapdeclassement.php','');" /></td>
                <td align="right"><span class="leftHeader">
                  <input name="Enregistrer3" type="button" class="button" id="Enregistrer3"  value='<?php echo getlang(188); ?>'   onclick="OpenWin('genererexcelrapdeclassement.php','Fichier Excel');" />
                </span></td>
                </tr>
            </tbody>
          </table>       </td> </tr>
        </table>
          <!-- Fin Affichage -->
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
