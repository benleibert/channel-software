<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['par_prd']['MODIF']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcproduit.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_prd'];

(isset($_SESSION['DATA_PRD']['CODE_PRODUIT']) ? $codeproduit = $_SESSION['DATA_PRD']['CODE_PRODUIT']: $codeproduit ='');
(isset($_SESSION['DATA_PRD']['ID_UNITE']) ? $unite = $_SESSION['DATA_PRD']['ID_UNITE']: $unite ='');
(isset($_SESSION['DATA_PRD']['CODE_SOUSCATEGORIE']) ? $codesouscategorie = $_SESSION['DATA_PRD']['CODE_SOUSCATEGORIE']: $codesouscategorie ='');
(isset($_SESSION['DATA_PRD']['PRD_LIBELLE']) ? $produit = $_SESSION['DATA_PRD']['PRD_LIBELLE']: $produit ='');
(isset($_SESSION['DATA_PRD']['PRD_DESCRIP']) ? $description = $_SESSION['DATA_PRD']['PRD_DESCRIP']: $description ='');
(isset($_SESSION['DATA_PRD']['CONDITIONNE']) ? $conditionne = $_SESSION['DATA_PRD']['CONDITIONNE']: $conditionne ='');
(isset($_SESSION['DATA_PRD']['PRD_PRIXACHAT']) ? $prixachat = $_SESSION['DATA_PRD']['PRD_PRIXACHAT']: $prixachat ='');
(isset($_SESSION['DATA_PRD']['PRD_PRIXREVIENT']) ? $prixrevient = $_SESSION['DATA_PRD']['PRD_PRIXREVIENT']: $prixrevient ='');
(isset($_SESSION['DATA_PRD']['PRD_PRIXVENTE']) ? $prixvente = $_SESSION['DATA_PRD']['PRD_PRIXVENTE']: $prixvente ='');
(isset($_SESSION['DATA_PRD']['PRD_SEUILMIN']) ? $seuilmin = $_SESSION['DATA_PRD']['PRD_SEUILMIN']: $seuilmin ='');
(isset($_SESSION['DATA_PRD']['PRD_SEUILMAX']) ? $seuilmax = $_SESSION['DATA_PRD']['PRD_SEUILMAX']: $seuilmax ='');

(isset($_SESSION['DATA_PRD']['PRD_PRIXACHATN2']) ? $prixachatn2 = $_SESSION['DATA_PRD']['PRD_PRIXACHATN2']: $prixachatn2 ='');
(isset($_SESSION['DATA_PRD']['PRD_PRIXREVIENTN2']) ? $prixrevientn2 = $_SESSION['DATA_PRD']['PRD_PRIXREVIENTN2']: $prixrevientn2 ='');
(isset($_SESSION['DATA_PRD']['PRD_PRIXVENTEN2']) ? $prixventen2 = $_SESSION['DATA_PRD']['PRD_PRIXVENTEN2']: $prixventen2 ='');
(isset($_SESSION['DATA_PRD']['PRD_SEUILMINN2']) ? $seuilminn2 = $_SESSION['DATA_PRD']['PRD_SEUILMINN2']: $seuilminn2 ='');
(isset($_SESSION['DATA_PRD']['PRD_SEUILMAXN2']) ? $seuilmaxn2 = $_SESSION['DATA_PRD']['PRD_SEUILMAXN2']: $seuilmaxn2 ='');


(isset($_SESSION['DATA_PRD']['CODESOUSGROUP']) ? $sousgroupe = $_SESSION['DATA_PRD']['CODESOUSGROUP']: $sousgroupe ='');
(isset($_SESSION['DATA_PRD']['PRD_TRACEUR']) ? $traceur = $_SESSION['DATA_PRD']['PRD_TRACEUR']: $traceur ='');
($traceur=='TRACEUR' ? $checked2= 'checked="checked"' : $checked2= '');
($conditionne==1 ? $checked= 'checked="checked"' : $checked= '');
if($prixachat!='')  { $prixachat = preg_replace('/\./',',' , $prixachat);}
if($prixrevient!='') { $prixrevient = preg_replace('/\./',','  , $prixrevient);}
if($prixvente!='') { $prixvente = preg_replace('/\./',','  , $prixvente);}

if($prixachatn2!='')  { $prixachatn2 = preg_replace('/\./',',' , $prixachatn2);}
if($prixrevientn2!='') { $prixrevientn2 = preg_replace('/\./',','  , $prixrevientn2);}
if($prixventen2!='') { $prixventen2 = preg_replace('/\./',','  , $prixventen2);}

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


<script type="text/javascript">


function validateForm(){
        var codeproduit = trimAll(document.formadd.codeproduit.value);
		var produit = trimAll(document.formadd.produit.value);
		var souscategorie = trimAll(document.formadd.souscategorie.options[document.formadd.souscategorie.selectedIndex].value);
		var unite = trimAll(document.formadd.unite.options[document.formadd.unite.selectedIndex].value);
		//var ration = trimAll(document.formadd.ration.value);
		//var prixplat = trimAll(document.formadd.prixplat.value);
		var msg = '';

		if(codeproduit == '') {
        	msg += '- Veuillez saisir le code du produit.\n';
        }
		if(souscategorie == "0") {
        	msg += '- Veuillez sélectionner la sous-catégorie.\n';
        }
		if(produit == "") {
        	msg += '- Veuillez saisir le libellé du produit.\n';
        }
		if(unite == "0") {
        	msg += '- Veuillez sélectionner l\'unité de mesure.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
			document.formadd.submit();
        }
}

function go(){
	if(document.getElementById('codeproduit').value != document.getElementById('oldcodeproduit').value){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('codeproduit').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbproduit.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeproduit').value;
	xhr.send("codeproduit="+id);
	}
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
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
              <tr>
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> -> <?php echo getlang(119); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbproduit.php?do=update" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(431); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(257); ?>&nbsp;:&nbsp;</td>
                            <td width="358" align="left" class="text"><input name="codeproduit" type="text" class="formStyle" id="codeproduit" onblur="go();" value="<?php echo  $codeproduit; ?>" />
                              <span class="mandatory" id="msg2">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(159); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="souscategorie" id="souscategorie" class="formStyle" onchange="PA();">
                              <option value="0"></option>
                              <?php echo selectsousCategorie($codesouscategorie); ?>
                              </select>
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(161); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="sousgroupe" id="sousgroupe" class="formStyle" onchange="PA();">
                              <option value="0"></option>
                              <?php echo selectsousGroupe($sousgroupe); ?>
                            </select>
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(199); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="produit" type="text" class="formStyle" id="produit" value="<?php echo  $produit; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(176); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="unite" id="unite" class="formStyle">
                              <option value="0"></option>
                              <?php echo selectUnite($unite); ?>
                              </select>
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(465); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="description" type="text" class="formStyle" id="description" value="<?php echo  $description; ?>" /></td>
                          </tr>
                          <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" colspan="2"><?php echo getlang(259); ?></td>
                    </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(260); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="seuilmin" type="text" class="formStyle" id="seuilmin" value="<?php echo  $seuilmin; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(261); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="seuilmax" type="text" class="formStyle" id="seuilmax" value="<?php echo  $seuilmax; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(221); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixachat" type="text" class="formStyle" id="prixachat" value="<?php echo  $prixachat; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(262); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixrevient" type="text" class="formStyle" id="prixrevient" value="<?php echo  $prixrevient; ?>"  /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(220); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixvente" type="text" class="formStyle" id="prixvente" value="<?php echo  $prixvente; ?>"  /></td>
                          </tr>
                          <!--
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Ration journali&egrave;re&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="ration" type="text" class="formStyle" id="ration" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Prix du plat&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixplat" type="text" class="formStyle" id="prixplat" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          -->
                          <tr class="header2Bg">
                            <td align="left" valign="top" class="boldText" colspan="2"><?php echo getlang(258); ?></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(260); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="seuilminn2" type="text" class="formStyle" id="seuilmin3" value="<?php echo  $seuilminn2; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(261); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="seuilmaxn2" type="text" class="formStyle" id="seuilmax3" value="<?php echo  $seuilmaxn2; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(221); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixachatn2" type="text" class="formStyle" id="prixachatn2" value="<?php echo  $prixachatn2; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(262); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixrevientn2" type="text" class="formStyle" id="prixrevientn2" value="<?php echo  $prixrevientn2; ?>"  /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(220); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixventen2" type="text" class="formStyle" id="prixventen2" value="<?php echo  $prixventen2; ?>"  /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(116); ?> <?php echo getlang(384); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="traceur" type="checkbox" id="traceur" value="TRACEUR" <?php echo $checked2 ?> />

                              <span class="mandatory"></span></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice2" type="hidden" id="statusexercice2" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name="oldcodeproduit" type="hidden" id="oldcodeproduit" value="<?php echo $codeproduit; ?>" /></td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200">&nbsp;</td>
                            <td><input name="Suivant2" type="button" class="button" id="Suivant2"  value="<?php echo getlang(190); ?>" onclick="validateForm();" />
                              <input name="Retablir2" type="reset" class="button" id="Retablir2"  value='<?php echo getlang(193); ?>' /></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table>
                </form></td>
              </tr>
            </table></td>
          </tr>
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
