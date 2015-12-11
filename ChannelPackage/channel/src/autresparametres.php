<?php
session_start();
require_once('../src/topmenus.php');				//The menubar
require_once('../lib/phpfuncLib.php');		//All commun functions
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:phpfuncindex.php?myaction=LOGOUT");

$_SESSION['DATA_PERS']=-1;
$listeGroupes =listeGroupes();
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
<script type="text/javascript">
function validateForm(){
        var numMatricule = trimAll(document.AddPersonnesForm.numMatricule.value);
		var nomPrenoms = trimAll(document.AddPersonnesForm.nomPrenoms.value);
		var fonction = trimAll(document.AddPersonnesForm.fonction.value);
		var nomUtilisateur = trimAll(document.AddPersonnesForm.nomUtilisateur.value);
		var motPasse1 = trimAll(document.AddPersonnesForm.motPasse1.value);
		var motPasse2 = trimAll(document.AddPersonnesForm.motPasse2.value);
		var email = trimAll(document.AddPersonnesForm.email.value);
		var msg = '';

		if(numMatricule == '') {
        	msg += '- Veuillez préciser le n° matricule.\n';
        }
		if(nomPrenoms == "") {
        	msg += '- Veuillez entrer le nom et prénoms.\n';
        }
		if(fonction == "") {
        	msg += '- Veuillez entrer la fonction.\n';
        }
		if(nomUtilisateur == "") {
        	msg += '- Veuillez entrer le nom d\'utilisateur.\n';
        }
		if(motPasse1 == "") {
        	msg += '- Veuillez entrer le mot de passe.\n';
        }
		if(motPasse2 == "") {
        	msg += '- Veuillez confirmer le mot de passe.\n';
        }
		if(motPasse1 != '' && motPasse2 != '' && motPasse1 != motPasse2) {
        	msg += '- Veuillez entrer le même mot de passe.\n';
        }
		if(email != '' && !isEmailId(email)) {
        	msg += '- Veuillez entrer un e-mail valide.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	document.AddPersonnesForm.myaction.value='ETAPE2';
			document.AddPersonnesForm.submit();
        }
}

function go(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfuncpersonnes.php?test=NUMMLLE",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('numMatricule').value;
	xhr.send("numMatricule="+id);
}

function go1(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg1').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","phpfuncpersonnes.php?test=LOGIN",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('nomUtilisateur').value;
	xhr.send("nomUtilisateur="+id);
}
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

function doPersonalize()
{
	  var str = window.location.href;
	  re = /&/gi;
	  newstr=str.replace(re, "*");
	  MM_openBrWindow('/webclient/common/jsp/skinSelection.jsp?linkUrl='+newstr,'skins','left=300,top=150,width=500,height=450')
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
	  <a href="" onClick="JavaScript:window.open('/webclient/common/jsp/registerDialog.jsp?UserType=R','License','left=500,top=100,width=500,height=275')" >License</a>
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
            <form name="stockform" action="../sources/search.php" method="post" onsubmit=''>
            <td align="left" class="leftHeader">
            <?php echo EXBG_MAG; ?></td>
			<td align="right">&nbsp;

              </td>
            <td><input name="Go" type="submit" class="buttonGo" value="GO"></td>
            	<input type="hidden" name="requestid" value="SNAPSHOT">

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
            <td width="180"><?php
			$selectedTab = $_GET['selectedTab'];
			include '../src/menus.php';
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
                  <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> </td>
                </tr>
                <tr>
                  <td colspan=2 align="left" valign="top"><form name="AddParamForm" action="../src/phpfuncparamaters.php" method="POST">
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >Soci&eacute;ti&eacute; - Organisme - Particulier&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                            <tbody>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">N&deg; produit&nbsp;:&nbsp;</td>
                                <td width="358" align="left" class="text"><input name="numProduit" type="text" class="formStyle" id="numProduit" onBlur="go();">
                                    <span class="mandatory" id="msg"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(103); ?>&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="nom" type="text" class="formStyle" id="nom">
                                    <span class="mandatory"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(65); ?> ou activit&eacute; :&nbsp;</td>
                                <td align="left" class="text"><input name="fonction" type="text" class="formStyle" id="fonction">
                                    <span class="mandatory"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(5); ?>&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="adresse" type="text" class="formStyle" id="adresse">
                                    <span class="mandatory"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(344); ?>&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="ville" type="text" class="formStyle" id="ville">
                                    <span class="mandatory"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(165); ?>&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="telephone" type="text" class="formStyle" id="telephone">
                                    <span class="mandatory"></span> </td>
                              </tr>
							  <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">Fax&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="fax" type="text" class="formStyle" id="fax">
                                    <span class="mandatory"></span> </td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(51); ?>&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="email" type="text" class="formStyle" id="email">
                                    <span class="mandatory"></span> </td>
                              </tr>
							  <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">Site internet&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="site" type="text" class="formStyle" id="site">
                                    <span class="mandatory"></span> </td>
                              </tr>
							  <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">Logo&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="logo" type="file" class="formStyle" id="logo">
                                    <span class="mandatory"></span> </td>
                              </tr>
							  <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text">Entête&nbsp;:&nbsp;</td>
                                <td align="left" class="text"><input name="entete" type="file" class="formStyle" id="entete">
                                    <span class="mandatory"></span> </td>
                              </tr>
                            <input type="hidden" name="existingGroups" value='GL_USERistrators' >
                          </table></td>
                        </tr>
                      </table>
                          <br>
                      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Param&egrave;tre du logiciel</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="900" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top">
                                  <td width="200" align=right valign="middle" class="text">Validit&eacute; d'un appel d'offre&nbsp;:&nbsp;</td>
                                  <td align="left" class="text" width="250"><input name="validAO" type="text" class="formStyle" id="validAO">
                                      <span class="mandatory" id="msg1"></span> </td>
								<td width="400" align=left valign="middle" class="text"><i> (jours) Passer ce délai sans validation, l'appel d'offre est supprimé</i></td>
                                </tr>
                                <tr align="left" valign="top">
                                  <td width="200" align=right valign="middle" class="text">Validit&eacute; d'un besoin exprim&eacute;&nbsp;:&nbsp;</td>
                                  <td align="left" class="text"><input name="validB" type="text" class="formStyle" id="validB">
                                      <span class="mandatory" id="msg1"></span> </td>
									  <td width="400" align=left valign="middle" class="text"><i> (jours) Passer ce délai sans validation, l'appel d'offre est supprimé</i></td>
                                </tr>
								<tr align="left" valign="top">
                                  <td width="200" align=right valign="middle" class="text">Validit&eacute; d'un bon d'entr&eacute;e&nbsp;:&nbsp;</td>
                                  <td align="left" class="text"><input name="validB" type="text" class="formStyle" id="validB">
                                      <span class="mandatory" id="msg1"></span> </td>
									  <td width="400" align=left valign="middle" class="text"><i> (jours) Passer ce délai sans validation, le bon d'entr&eacute;e est supprimé</i></td>
                                </tr>
								<tr align="left" valign="top">
                                  <td width="200" align=right valign="middle" class="text">Validit&eacute; d'un bon de sortie&nbsp;:&nbsp;</td>
                                  <td align="left" class="text"><input name="validB" type="text" class="formStyle" id="validB">
                                      <span class="mandatory" id="msg1"></span> </td>
									  <td width="400" align=left valign="middle" class="text"><i> (jours) Passer ce délai sans validation, le bon de sortie est supprimé</i></td>
                                </tr>
								<tr>
                                  <td colspan="2" align="right" valign="middle" background="file:///C|/wamp/www/sources/adduser_files/1spacer0.gif" class="text"></td>
                                </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(97); ?>&nbsp;:&nbsp;</td>
                                <td class="text"><input name="motPasse1" type="password" class="formStyle" id="motPasse1">
                                    <span class="mandatory"></span></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" valign="middle" background="file:///C|/wamp/www/sources/adduser_files/1spacer0.gif" class="text"></td>
                              </tr>
                              <tr align="left" valign="top">
                                <td width="200" align=right valign="middle" class="text"><?php echo getlang(39); ?>&nbsp;:&nbsp;</td>
                                <td class="text"><input name="motPasse2" type="password" class="formStyle" id="motPasse2" >
                                    <span class="mandatory"></span></td>
                              </tr>
                              <tr>
                                <td colspan="2">
								<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
						<input name='myaction' type='hidden' id="myaction" value=''></td>
                              </tr>
								<tr align="left" valign="top">
                                <td width="200">&nbsp;</td>
                                <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(194); ?>" onClick="validateForm();"> <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(193); ?>'>
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
