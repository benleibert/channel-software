<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['par_per']['MODIF']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcpersonnel.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_per'];

(isset($_SESSION['DATA_PER']['NUM_MLLE']) ? $nummlle = $_SESSION['DATA_PER']['NUM_MLLE']: $nummlle ='');
(isset($_SESSION['DATA_PER']['CODE_MAGASIN']) ? $service = $_SESSION['DATA_PER']['CODE_MAGASIN']: $service ='');
(isset($_SESSION['DATA_PER']['PERS_NOM']) ? $nom = $_SESSION['DATA_PER']['PERS_NOM']: $nom ='');
(isset($_SESSION['DATA_PER']['PERS_PRENOMS']) ? $prenom = $_SESSION['DATA_PER']['PERS_PRENOMS']: $prenom ='');
(isset($_SESSION['DATA_PER']['PERS_ADRESSE']) ? $adresse = $_SESSION['DATA_PER']['PERS_ADRESSE']: $adresse ='');
(isset($_SESSION['DATA_PER']['PERS_EMAIL']) ? $email = $_SESSION['DATA_PER']['PERS_EMAIL']: $email ='');
(isset($_SESSION['DATA_PER']['PERS_TEL']) ? $tel = $_SESSION['DATA_PER']['PERS_TEL']: $tel ='');
(isset($_SESSION['DATA_PER']['PERS_FONCTION']) ? $fonction = $_SESSION['DATA_PER']['PERS_FONCTION']: $fonction ='');


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
        var service = trimAll(document.formadd.service.options[document.formadd.service.selectedIndex].value);
		var nummlle = trimAll(document.formadd.nummlle.value);
		var nom = trimAll(document.formadd.nom.value);
		var tel = trimAll(document.formadd.tel.value);
		var email = trimAll(document.formadd.email.value);
		var msg = '';

		if(nummlle == '') {
        	msg += '- Veuillez saisir le numéro matricule.\n';
        }
		if(nom == '') {
        	msg += '- Veuillez saisir le nom.\n';
        }
		if(service == '0') {
        	msg += '- Veuillez Sélectionner le site bénéficiaire.\n';
        }
		if(tel == "") {
        	msg += '- Veuillez saisir le numéro de téléphone.\n';
        }
		if(email != '' && !isEmailId(email)) {
        	msg += '- Veuillez saisir une adresse électronique correcte.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
			document.formadd.submit();
        }
}

function go(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('nummlle').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbpersonnel.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('nummlle').value;
	xhr.send("nummlle="+id);
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> -> <?php echo getlang(110); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbpersonnel.php?do=update" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(440); ?> <?php echo getlang(109); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(105); ?>&nbsp;:&nbsp;</td>
                            <td width="358" align="left" class="text"><input name="nummlle" type="text" class="formStyle" id="nummlle" onblur="go();" value="<?php echo  $nummlle; ?>" />
                              <span class="mandatory" id="msg"></span><span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(103); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="nom" type="text" class="formStyle" id="nom" value="<?php echo  $nom; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(115); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prenom" type="text" class="formStyle" id="prenom" value="<?php echo  $prenom; ?>" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(153); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="service" id="service" class="formStyle">
                              <option value="0"></option>
                              <?php echo selectService($service); ?>
                            </select>
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(165); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="tel" type="text" class="formStyle" id="tel" value="<?php echo  $tel; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(5); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="adresse" type="text" class="formStyle" id="adresse" value="<?php echo  $adresse; ?>" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(51); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="email" type="text" class="formStyle" id="email" value="<?php echo  $email; ?>" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(65); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="fonction" type="text" class="formStyle" id="fonction" value="<?php echo  $fonction; ?>" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr>
                            <td><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name='oldnummlle' type='hidden' id="oldnummlle" value="<?php echo  $nummlle; ?>" /></td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200">&nbsp;</td>
                            <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(190); ?>" onclick="validateForm();" />
                              <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(193); ?>'
 /></td>
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
