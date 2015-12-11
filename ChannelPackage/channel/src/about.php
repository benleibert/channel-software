<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');


//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = aideMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);
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
.Style3 {
	font-size: 36px;
	font-weight: bold;
	color: #CC0000;
}
.Style4 {
	font-size: xx-large;
	font-family: "Agency FB";
}
.Style5 {
	font-size: 9;
	font-style: italic;
	font-weight: bold;
}
.Style7 {font-size: 9; font-style: italic; }
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
            <td width="180">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" height="80%" align="left" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
      <tr class="header2Bg">
        <td align="left" valign="top" class="boldText" >&nbsp;A propos du logiciel</td>
      </tr>
      <tr>
        <td class="text" align="center"><table width="911" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
          <tbody>
            
            
            <tr align="left" valign="top">
              <td width="891" align="right" valign="middle" class="text"><div align="center" class="Style3">CHANNEL 2 Version 1.0 </div></td>
              </tr>
            
            <tr align="left" valign="top">
              <td align="right" valign="middle" class="text"><p align="justify" class="Style4">Le logiciel <strong>CHANNEL</strong> a été mis au point pour  répondre directement aux demandes de nombreux pays souhaitant un logiciel de  gestion de produits de santé qui soit suffisamment simple pour pouvoir être utilisé dans  des entrepôts et des lieux où l’emploi des ordinateurs est limité et où, de ce  fait, les capacités d’utilisation sont restreintes.<br />
                Il a été créé  pour fournir des rapports de gestion utiles et pour être un outil flexible et  facilement adaptable en fonction des contextes nationaux.</p></td>
              </tr>
            <tr>
              <td class="text" align="center"><div align="left" class="Style5">Equipe technique : </div></td>
              </tr>
            <tr>
              <td class="text" align="center"><div align="left" class="Style7"><strong>M. Kadéba Lazoumou</strong>, Informaticien, Télé : +226 70267130 </div></td>
              </tr>
            <tr>
              <td class="text" align="center"><div align="left" class="Style7"><strong>M. Guiré Kassim</strong>, Statisticien informaticien, Télé : +226 70268711 </div></td>
              </tr>
            <tr>
              <td class="text" align="center"><div align="left" class="Style7"><strong>Dr. Koudougou Joachim</strong>, Logisticien, Télé : +226 72945526 </div></td>
              </tr>
            <tr>
              <td class="text" align="center">&nbsp;</td>
              </tr>
          </tbody>
        </table></td>
      </tr>
    </table>      <br>
    

</td>
  </tr>
  <tr>
    <td height="10%">&nbsp;</td>
    <td height="10%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
