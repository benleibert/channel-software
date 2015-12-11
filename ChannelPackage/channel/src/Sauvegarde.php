<?php
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['data_exp']['VISIBLE']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcetat.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = basededonneesMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//Left Menu

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['int_sto'];

if(isset($_SESSION['GL_USER']['MAGASIN']) && $_SESSION['GL_USER']['MAGASIN']==''){
	header('location:home.php?selectedTab=home');
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


<!-- Pickdate -->
<link type="text/css" href="../lib/themes/base/ui.all.css" rel="stylesheet" />
<link type="text/css" href="../lib/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../lib/ui/ui.core.js"></script>
<script type="text/javascript" src="../lib/ui/ui.datepicker.js"></script>

<!-- Begin of JS  -->
<script type="text/javascript" src="../lib/JQuerySpinBtn.js"></script>

<script type="text/javascript">	$(function() {
		$('#datepicker1').datepicker({
			showButtonPanel: true,
			dateFormat: "dd/mm/yy" });
	});

</script>

<script type="text/javascript">
	$(function() {
		$('#datepicker2').datepicker({
			showButtonPanel: true
		});
	});
</script>

<script type="text/javascript">
	$(function() {
		$('#datepicker3').datepicker({
			showButtonPanel: true
		});
	});
</script>
<script type="text/javascript" language="javascript">

		// Apply the SpinButton code to the appropriate INPUT elements:
		$(function(){

			$("INPUT.spin-button").SpinButton({min:1});

		});

</script>
<script type="text/javascript">

function validateFormS(){
	var k=0;
	for (i = 0; i < document.formadd.elements.length; i++) {
       		if(document.formadd.elements[i].checked==true) k++;
    }
	if(k==0){
		alert('Veuillez sélectionner les tables à exporter');
	}
	else {
		document.formadd.submit();
    }
}

function fillProvince(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('province').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbetat.php?do=fillProvince",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('region').options[document.getElementById('region').selectedIndex].value;
	xhr.send("region="+id);
}




function validateForm(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('cantine').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbetat.php?do=fillMagasin",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('province').options[document.getElementById('province').selectedIndex].value;
	xhr.send("province="+id);
}

function doMyAction(myform){
	if(document.formadd.toggleAll.checked == true){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.formadd.elements.length; i++) {
       		document.formadd.elements[i].checked=true;
    	}
	}
	if(document.formadd.toggleAll.checked == false){
	//alert(document.ListingForm.elements.length);
		for (i = 0; i < document.formadd.elements.length; i++) {
       		document.formadd.elements[i].checked=false;
    	}
	}
    return false;
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
.Style3 {color: #FF9933}
.Style5 {
	color: #FF9900;
	font-weight: bold;
}
.Style6 {color: #0000FF}
.Style7 {font-weight: bold}
.Style8 {color: #003300}
.Style9 {font-weight: bold}
.Style11 {color: #000000; font-weight: bold; }
.Style12 {
	color: #FFFFFF;
	font-weight: bold;
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
<table width="100%" height="80%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="80%" align="left" valign="top"><table width="1445" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="221" rowspan="40" align="left" valign="top"><table width="180" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td width="159"><?php echo $leftMenu; ?></td>
          </tr>
        </table></td>
              <form action="dbbackup.php?do=sauve" method="post" name="formadd" id="formadd">
		<tr>
        <td colspan="14" align="left" valign="top" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(334); ?></td>
      </tr>
      <tr>
        <td colspan="14" align="left" valign="top" bgcolor="#CCCCCC" class="boldText"><?php echo getlang(330); ?></td>
      </tr>
      <tr>
        <td width="180" align="left" valign="top"><input name="beneficiaire" type="checkbox" id="beneficiaire" value="beneficiaire" />
          <?php echo getlang(16); ?></td>
        <td colspan="2" align="left" valign="top"><div align="center" class="Style11 text"><?php echo getlang(426); ?> -&gt; <?php echo getlang(152); ?> </div></td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="bonsortie" type="checkbox" id="bonsortie" value="bonsortie" />
          <?php echo getlang(237); ?></td>
        <td align="right" valign="top" class="Style11 text"><?php echo getlang(102); ?> : </td>
        <td align="left" valign="top" class="text"><select name="region" class="formStyle Style3 Style5" id="region" onchange="fillProvince();">
            <option value="0"><?php echo getlang(232); ?></option>
            <?php echo selectRegion(''); ?>
        </select></td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="19" align="left" valign="top"><input name="categorie" type="checkbox" id="categorie" value="categorie" />
          <?php echo getlang(19); ?></td>
        <td align="right" valign="top" class="Style6 text"><span class="Style11"><?php echo getlang(154); ?> : </span></td>
        <td align="left" valign="top" class="text"><select name="province" class="formStyle Style6 Style7" id="province" onchange="validateForm();">
            <option value="0">[<?php echo getlang(150); ?>]</option>
        </select></td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="commande" type="checkbox" id="commande" value="commande" />
          <?php echo getlang(36); ?></td>
        <td align="right" valign="top" class="Style11 text"><?php echo getlang(149); ?>&nbsp;:</td>
        <td align="left" valign="top" class="text"><select name="cantine" class="formStyle Style8 Style9" id="cantine" onchange="validateForm2();">
            <option value="0">[<?php echo getlang(149); ?>]</option>
        </select></td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="compte" type="checkbox" id="compte" value="compte" />
          <?php echo getlang(37); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="declass" type="checkbox" id="declass" value="declass" />
          <?php echo getlang(111); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="exercice" type="checkbox" id="exercice" value="exercice" />
          <?php echo getlang(338); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="fournisseur" type="checkbox" id="fournisseur" value="fournisseur" />
          <?php echo getlang(66); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="infogenerale" type="checkbox" id="infogenerale" value="infogenerale" />
          <?php echo getlang(337); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="inventaire" type="checkbox" id="inventaire" value="inventaire" />
          <?php echo getlang(73); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="livraison" type="checkbox" id="livraison" value="livraison" />
          <?php echo getlang(86); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="logs" type="checkbox" id="logs" value="logs" />
Logs </td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="magasin" type="checkbox" id="magasin" value="magasin" />
          <?php echo getlang(153); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="menu" type="checkbox" id="menu" value="menu" />
Menu </td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="mouvement" type="checkbox" id="mouvement" value="mouvement" />
          <?php echo getlang(336); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="personnel" type="checkbox" id="personnel" value="personnel" />
          <?php echo getlang(110); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="produit" type="checkbox" id="produit" value="produit" />
          <?php echo getlang(116); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="profil" type="checkbox" id="profil" value="profil" />
Profil </td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="profil_menu" type="checkbox" id="profil_menu" value="profil_menu" />
          <?php echo getlang(454); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="sitef" type="checkbox" id="sitef" value="sitef" />
          <?php echo getlang(154); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="sitec" type="checkbox" id="sitec" value="sitec" />
          <?php echo getlang(102); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="report" type="checkbox" id="report" value="report" />
          <?php echo getlang(143); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="souscategorie" type="checkbox" id="souscategorie" value="souscategorie" />
          <?php echo getlang(157); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="transfert" type="checkbox" id="transfert" value="transfert" />
          <?php echo getlang(166); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="typebeneficiaire" type="checkbox" id="typebeneficiaire" value="typebeneficiaire" />
          <?php echo getlang(169); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="typefournisseur" type="checkbox" id="typefournisseur" value="typefournisseur" />
          <?php echo getlang(175); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="unite" type="checkbox" id="unite" value="unite" />
          <?php echo getlang(204); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="sousgroupe" type="checkbox" id="sousgroupe" value="sousgroupe" />
          <?php echo getlang(158); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="affectesite" type="checkbox" id="affectesite" value="affectesite" />
          <?php echo getlang(6); ?></td>
        <td width="196" align="left" valign="top">&nbsp;</td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top" bgcolor="#0066FF">
          <input type="checkbox" name="toggleAll" value="checkbox" onclick="doMyAction();" />
          <span class="Style12"><?php echo getlang(216); ?> (Total : 36 tables)</span></td>
        <td width="196" align="left" valign="top"><input name="Vider" type="button" class="button" id="Vider"  value="<?php echo getlang(339); ?>" onclick="validateFormS();" /></td>
        <td width="250" align="left" valign="top"><input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(217); ?>' /></td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td width="196" align="left" valign="top"><input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
          <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" />
          <input name='nivsauv' type='hidden' id="nivsauv" /></td>
        <td width="250" align="left" valign="top">&nbsp;</td>
        <td width="548" align="left" valign="top">&nbsp;</td>
      </tr>
     </form>
   </table>
    </td>
	


	
</body>
</html>
