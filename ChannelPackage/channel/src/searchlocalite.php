<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funclocalite.php');

unset($_SESSION['WHERE']);
$droit = getDroit('GRP_PERSONNEL ,GRP_UTILISATEUR, GRP_GROUPE, GRP_LOG,	GRP_CATEGORIE, GRP_PRODUIT,	GRP_CONDITIONNEMENT, GRP_USTENSILE,	GRP_UNITE, GRP_CONVERSION, GRP_BAREME, GRP_REGION, GRP_PROVINCE, GRP_TYPESERVICE, GRP_SERVICE, GRP_MAGASIN, GRP_FOURNISSEUR, GRP_TYPEBENEFICIAIRE, GRP_BENEFICIAIRE, GRP_EXERCICE, GRP_TYPEDOTATION, GRP_PARAMETRE, GRP_DB', $_SESSION['GL_USER']['GROUPE']);

$droitTOPMENUS = getDroitTOPMENUS( $_SESSION['GL_USER']['GROUPE']);

$droitMAJ = getDroitMAJ('GRP_LOCALITE', $_SESSION['GL_USER']['GROUPE']);

//Numuro page
(isset($_GET['page']) ? $page = $_GET['page'] : $page=1);
//Nbre d'élément par page
(isset($_POST['viewLength'])  ? $_SESSION['GL_USER']['ELEMENT']= $_POST['viewLength']: '');

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
        var grplocalite = trimAll(document.formadd.grplocalite.options[document.formadd.grplocalite.selectedIndex].value);
		var localite = trimAll(document.formadd.localite.value);
		var msg = '';
		
		if(grplocalite == '0' && localite=='') {
        	msg += '- Veuillez entrer un critère de <?php echo getlang(139); ?>.\n';
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
			if(retour !='') document.getElementById('codeCategorie').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfunccategories.php?test=CODECATEGORIE",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeCategorie').value;
	xhr.send("codeCategorie="+id);
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
            <td width="180"><?php 
			$selectedTab = $_GET['selectedTab'];
			echo parametersMenus($selectedTab , $droit);
			//echo menus($selectedTab, $_SESSION['GL_USER']['EXERCICE']);
			?></td>
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> &rarr; Les localit&eacute;s</td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="../src/localite.php?selectedTab=par&amp;do=search" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(139); ?> une localit&eacute;</td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Type localit&eacute;&nbsp;:&nbsp;</td>
                            <td width="358" align="left" class="text"><select name="grplocalite" id="grplocalite" class="formStyle">
                              <option value="0"></option>
                              <?php echo selectTypeLocalite(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(103); ?> localit&eacute;&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="localite" type="text" class="formStyle" id="localite" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">D&eacute;pendance :&nbsp;</td>
                            <td align="left" class="text"><input name="xdependance" type="text" class="formStyle" id="xdependance" readonly="readonly" />
                              <input name="dependance" type="hidden" class="formStyle" id="dependance" />
                              <input name="openf" type="button" class="button"  title="Etablissements" value="..." onclick="OpenWin('listedependance.php','Liste');" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" /></td>
                            <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(139); ?>" onclick="validateForm();" />
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
