<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['data_exp']['VISIBLE']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = basededonneesMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//Message
$msg = '';
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

function validateForm(){
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
            <td width="43"  bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(334); ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>

          <tr>
            <td align="left" valign="top" class="text">
              <p><?php echo getlang(335); ?></p>
              <form action="dbbackup.php?do=sauve" method="post" name="formadd" id="formadd">
                <ul>
                  <li>                    <input name="beneficiaire" type="checkbox" id="beneficiaire" value="beneficiaire"  />
                  <?php echo getlang(16); ?></li>
<li>                  <input name="bonsortie" type="checkbox" id="bonsortie" value="bonsortie"  /> 
                 <?php echo getlang(237); ?></li>
<li>                  <input name="categorie" type="checkbox" id="categorie" value="categorie"  /> 
                  <?php echo getlang(19); ?></li>
<li>                  <input name="commande" type="checkbox" id="commande" value="commande"  /> 
                  <?php echo getlang(36); ?></li>
<li>
                  <input name="compte" type="checkbox" id="compte" value="compte"  /> 
                  <?php echo getlang(37); ?></li>
                <li>
                  <input name="declass" type="checkbox" id="declass" value="declass"  /> 
                  <?php echo getlang(111); ?></li>
                <li>
                  <input name="exercice" type="checkbox" id="exercice" value="exercice"  /> 
                  <?php echo getlang(338); ?></li>
                <li>
                  <input name="fournisseur" type="checkbox" id="fournisseur" value="fournisseur"  /> 
                  <?php echo getlang(66); ?></li>
                <li>                  <input name="infogenerale" type="checkbox" id="infogenerale" value="infogenerale"  /> 
                 <?php echo getlang(337); ?> </li>
<li>                  <input name="inventaire" type="checkbox" id="inventaire" value="inventaire"  /> 
                  <?php echo getlang(73); ?></li>
<li>                  <input name="livraison" type="checkbox" id="livraison" value="livraison"  /> 
                  <?php echo getlang(86); ?></li>
<li>
                  <input name="logs" type="checkbox" id="logs" value="logs"  /> 
                  Logs </li>
                <li>                  <input name="magasin" type="checkbox" id="magasin" value="magasin"  /> 
                  <?php echo getlang(153); ?></li><li>
                  <input name="menu" type="checkbox" id="menu" value="menu"  /> 
                  Menu </li>
                <li>
				<input name="mouvement" type="checkbox" id="mouvement" value="mouvement"  /> 
                  <?php echo getlang(336); ?></li>
<li>                  <input name="personnel" type="checkbox" id="personnel" value="personnel"  /> 
                  <?php echo getlang(110); ?></li>
<li>                  <input name="produit" type="checkbox" id="produit" value="produit"  /> 
                  <?php echo getlang(116); ?></li>
<li>
                  <input name="profil" type="checkbox" id="profil" value="profil"  /> 
                  Profil </li>
                <li>                  <input name="profil_menu" type="checkbox" id="profil_menu" value="profil_menu"  /> 
                 <?php echo getlang(454); ?> </li>
<li>                  <input name="province" type="checkbox" id="province" value="province"  /> 
                  <?php echo getlang(154); ?></li>
<li>                  <input name="region" type="checkbox" id="region" value="region"  /> 
                  <?php echo getlang(102); ?></li>
<li>                  <input name="report" type="checkbox" id="report" value="report"  /> 
                  <?php echo getlang(143); ?></li>
<li>                  <input name="souscategorie" type="checkbox" id="souscategorie" value="souscategorie"  /> 
                  <?php echo getlang(157); ?> </li>
<li>                  <input name="transfert" type="checkbox" id="transfert" value="transfert"  /> 
                  <?php echo getlang(166); ?></li>
<li>                  <input name="typebeneficiaire" type="checkbox" id="typebeneficiaire" value="typebeneficiaire"  /> 
                  <?php echo getlang(169); ?> </li>
<li>
                  <input name="typefournisseur" type="checkbox" id="typefournisseur" value="typefournisseur"  /> 
                  <?php echo getlang(175); ?></li>
                <li>                  <input name="unite" type="checkbox" id="unite" value="unite"  /> 
                  <?php echo getlang(204); ?></li>
                <li>
                  <input name="sousgroupe" type="checkbox" id="sousgroupe" value="sousgroupe">
                  <?php echo getlang(158); ?></li>
                <li>
                  <input name="affectesite" type="checkbox" id="affectesite" value="affectesite" />
                  <?php echo getlang(6); ?><br />
                </li>
                </ul>
                <p>Total : 36 tables<br />                  <input type="checkbox" name="toggleAll" value="checkbox" onclick="doMyAction();" />
                  <?php echo getlang(216); ?> </p>
                <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                  <tr class="header2Bg">
                    <td align="left" valign="top" class="boldText" ><?php echo getlang(39); ?> <?php echo getlang(148); ?> ?</td>
                  </tr>
                  <tr>
                    <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                      <tbody>
                        <tr align="left" valign="top">
                          <td><input name="Vider" type="button" class="button" id="Vider"  value="<?php echo getlang(339); ?>" onclick="validateForm();" />
                            <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(217); ?>' /></td>
                        </tr>
                      </tbody>
                    </table></td>
                  </tr>
              </table>
              </form></td>
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
