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
            <td width="43"  bgcolor="#FFCC66" class="leftHeader">Aide de Stock Pro v1.0 &rarr; Généralités</td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>
          
          <tr>
            <td align="left" valign="top" class="text">
            <!--  Debut affichage -->
            <table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><strong>La barre de menu principale</strong>
          <p>La barre de menu principale comprend: </p>
          <ul>
            <li><strong>Accueil</strong> : lieu où l&rsquo;utilisateur peut changer l&rsquo;exercice budgétaire et de cantine<br />
              <br />
            </li>
            <li><strong><?php echo getlang(36); ?>/livraisons </strong>: destiné à la saisie et la mise à jour des commandes fournisseurs et des  livraisons divers.<br />
              <br />
            </li>
            <li><strong>Programmation</strong> : réservé à la programmation des dotations du nouvel exercice budgétaire.<br />
              <br />
            </li>
            <li><strong>Mouvement  de stocks</strong> : regroupe tous les mouvements (dotation, livraison,  déclassement, reports, reconditionnement, …) sur le stock.<br />
              <br />
            </li>
            <li><strong><?php echo getlang(298); ?></strong> : comprend l&rsquo;inventaire théorique, la saisie de l&rsquo;inventaire physique et l&rsquo;état  du stock à une date donnée<br />
              <br />
            </li>
            <li><strong><?php echo getlang(54); ?>s  &amp; imprimables</strong> : reprend la liste des états importants<br />
              <br />
            </li>
            <li><strong>Paramétrage&nbsp;</strong>:  permet de paramétrer le logociel <br />
              <br />
            </li>
            <li><strong>Aide</strong> : l&rsquo;aide en ligne<br />
              <br />
            </li>
            <li><strong>Licence</strong> : est la licence du logiciel<br />
              <br />
            </li>
            <li><strong>Déconnexion</strong> : le bouton de déconnexion du logiciel</li>
          </ul>
          <p><img src="../images/sup_barre.jpg" width="1059" height="52" /></p>
          <p><br />
            <strong>La barre de menu secondaire</strong></p>
<p>La barre de menu secondaire est dépendante de la barre de  menu principale. <br />
  En effet en fonction de menu principal, le menu secondaire  s&rsquo;affiche. Par exemple le menu &ldquo;<strong><?php echo getlang(36); ?>/livraisons</strong>&rdquo;</p>
<p><img src="../images/sup_cde.jpg" width="582" height="51" /></p>
<p><strong><u>NB :</u></strong> Les  menus visibles sont fonctions des  droits  attribués à chaque groupe d&rsquo;utilisateur. <br />
  Et chaque utilisateur appartient à un  groupe. Donc verra ou pas certains menus. <br />
  Seul l&rsquo;GL_USERistrateur à droit à  toutes les options menu.</p>
<p>&nbsp;</p>
<p><strong>La présentation des listes<br />
</strong><img src="../images/sup_menucde.jpg" width="146" height="212" hspace="10" vspace="10" align="left" /></p>
<p>Les listes sont les données relatives à chaque rubrique  (commande, livraison, etc.) <br />
  et existant dans le logiciel.</p>
<p><br />
  <img src="../images/sup_detail.jpg" width="590" height="300" /></p>
<p><strong><u>NB :</u></strong> Tous  ces boutons s&rsquo;affichent si l&rsquo;utilisateur à les droits requis.</p>
<p><img src="../images/sup_supp.jpg" width="255" height="135" /></p>
<p><strong><u>NB&nbsp;:</u></strong> Pour supprimer une donnée, sélectionner la ligne  concernée et cliquer  sur le bouton  «&nbsp;<strong>Supprimer</strong>&nbsp;». <br />
  Pour  supprimer plusieurs données, cliquer sur «&nbsp;Cocher tous&nbsp;» ou bien  cocher une à une les lignes concernées et cliquer sur le bouton  «&nbsp;<strong>Supprimer</strong>&nbsp;». </p>
<p>Pour modifier une donnée,  sélectionner la ligne concernée et cliquer   sur le bouton «&nbsp;<strong>Modifier</strong>&nbsp;».  Il est impossible de modifier plusieurs lignes à la fois.</p>
<p>Pour valider une donnée,  sélectionner la ligne concernée et cliquer   sur le bouton «&nbsp;<strong>Valider</strong>&nbsp;».  Il est impossible de valider plusieurs lignes à la fois.</p>
<p> <strong><em>Noter qu&rsquo;une ligne validée ne peut plus être  supprimée, modifiée ou validée à nouveau.</em></strong></p></td>
      </tr>
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
