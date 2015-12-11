<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcuser.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_uti'];
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
        var personnel = trimAll(document.formadd.personnel.value);
		var groupe = trimAll(document.formadd.groupe.options[document.formadd.groupe.selectedIndex].value);
		var compte = trimAll(document.formadd.compte.value);
		var motpasse1 = trimAll(document.formadd.motpasse1.value);
		var motpasse2 = trimAll(document.formadd.motpasse2.value);
		var langue = trimAll(document.formadd.langue.value);
		var msg = '';

		if(personnel == '0') {
        	msg += '- Veuillez sélectionner la propriétaire du compte.\n';
        }
		if(groupe == "0") {
        	msg += '- Veuillez sélectionner le groupe.\n';
        }
		if(compte == '') {
        	msg += '- Veuillez saisir le compte utilisateur.\n';
        }
		if(langue == '0') {
        	msg += '- Veuillez saisir la langue de travail.\n';
        }
		if(motpasse1 == '') {
        	msg += '- Veuillez saisir le mot de passe.\n';
        }
		if(motpasse2 == '') {
        	msg += '- Veuillez confirmer le mot de passe.\n';
        }
		if(motpasse1 != motpasse2) {
        	msg += '- Veuillez saisir les même mots de passe.\n';
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
			if(retour !='') document.getElementById('compte').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbuser.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('compte').value;
	xhr.send("compte="+id);
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
</head><body class="bodyBg">
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
                <td height="20" bgcolor="#FFCC66" class="leftHeader" nowrap="nowrap"><?php echo getlang(106); ?> -> <?php echo getlang(177); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbuser.php?do=add" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(11); ?> <?php echo getlang(177); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(109); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="personnel" class="formStyle" id="personnel">
                              <option value="0"></option>
                              <?php echo selectPersonnel(); ?>
                            </select>
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(171); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="groupe" class="formStyle" id="groupe">
                              <option value="0"></option>
                              <?php echo selectGroupe(); ?>
                            </select>
                              <span class="mandatory"> *</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(177); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="compte" type="text" class="formStyle" id="compte" onblur="go();" />
                              <span class="mandatory" > *</span><span class="mandatory" id="msg"> </span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(97); ?>&nbsp;:&nbsp;</td>
                            <td class="text"><input name="motpasse1" type="password" class="formStyle" id="motpasse1" />
                              <span class="mandatory"> *</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(39); ?>&nbsp;:&nbsp;</td>
                            <td class="text"><input name="motpasse2" type="password" class="formStyle" id="motpasse2" />
                              <span class="mandatory"></span> <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(460); ?>&nbsp;:</td>
                            <td align="left" class="text"><select name="langue" id="langue" class="formStyle">
                              <option value="1">Français</option>
                              <?php echo selectlangue(); ?>
                            </select>                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text">Statut <?php echo getlang(177); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>
<input name="statut" type="checkbox" value="1" checked="checked" />
<?php echo getlang(461); ?></label>
                              <label>
/                              <?php echo getlang(462); ?></label>
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200">&nbsp;</td>
                            <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(194); ?>" onclick="validateForm();" />
                              <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(193); ?>' /></td>
                          </tr>
                          <tr>
                            <td colspan="2"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name='myaction' type='hidden' id="myaction" value='' /></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table>
                  <!--
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Magasins g&eacute;r&eacute;s</td>
                    </tr>
                    <tr>
                      <td class="text" align="center">
                      <table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text">Magasin 1&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag1" class="formStyle" id="mag1">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                            </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text">Magasin 2&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag2" class="formStyle" id="mag2">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 3&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag3" class="formStyle" id="mag3">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 4&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag4" class="formStyle" id="mag4">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 5&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag5" class="formStyle" id="mag5">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 6&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag6" class="formStyle" id="mag6">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 7&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag7" class="formStyle" id="mag7">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                              </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Magasin 8&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><select name="mag8" class="formStyle" id="mag8">
                              <option value="0"></option>
                              <?php echo selectService(); ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          
                        </tbody>
                      </table></td>
                    </tr>
                  </table> -->
                  <p>&nbsp;</p>
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
