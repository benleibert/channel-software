<?php
session_start();
if($_SESSION['GL_USER']['SESSIONID'] != session_id())header("location:dbuser.php?do=logout");

//PHP functions librairy
require_once('../lib/phpfuncLib.php');	//All commun functions
require_once('menus.php');			//Menu functions
//require_once('funcaction.php');		//Profil functions
$userName = getField('LOGIN',$_SESSION['GL_USER']['LOGIN'],'LOGIN','compte');
$ilang=getCodelangue($userName);
if($ilang=='' ){$ilang='1';}

//Top Menu

$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = homeMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//Liste des exercices budgétaires
$listeExercice= listeExercice($_SESSION['GL_USER']['EXERCICE']);

//Liste des magasins
//$listeService= getUserServiceList($_SESSION['GL_USER']['LOGIN'], $_SESSION['GL_USER']['MAGASIN']);

//$listeProvince = selectProvince($_SESSION['GL_USER']['PROVINCE']);
$listeProvinceC = selectProvinceC($_SESSION['GL_USER']['LOGIN'],$_SESSION['GL_USER']['PROVINCE']);

$listeService = getUserServiceFormProvince($_SESSION['GL_USER']['LOGIN'],$_SESSION['GL_USER']['PROVINCE'], $_SESSION['GL_USER']['MAGASIN']);

$personnel = getPersonnel($_SESSION['GL_USER']['MLLE']);

$nom = $personnel['PERS_NOM'];
$prenom = $personnel['PERS_PRENOMS'];
$nummlle = $personnel['NUM_MLLE'];
$fonction = $personnel['PERS_FONCTION'];
$adresse = $personnel['PERS_ADRESSE'];
$service = $personnel['CODE_MAGASIN'];
$tel = $personnel['PERS_TEL'];
$email = $personnel['PERS_EMAIL'];
$msg = '';

if(isset($_SESSION['GL_USER']['PROVINCE']) && $_SESSION['GL_USER']['PROVINCE']==''){
	$text = getlang(326); 
	$msg = '<div class="errorMsg">'.(stripslashes($text)).'</div>';
}
if(isset($_SESSION['GL_USER']['MAGASIN']) && $_SESSION['GL_USER']['MAGASIN']==''){
	$text = getlang(184); 
	$msg = '<div class="errorMsg">'.(stripslashes($text)).'</div>';
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
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>
<script type="text/javascript">
function validateForm(){
        var exercice = trimAll(document.HomeForm.exercice.options[document.HomeForm.exercice.selectedIndex].value);
		var province = trimAll(document.HomeForm.province.options[document.HomeForm.province.selectedIndex].value);
		var msg = '';

		if(exercice == "00") {
        	msg += '- Veuillez sélectionner l\'exercice budgétaire.\n';
        }
		if(province == "00") {
        	msg += '- Veuillez sélectionner un site fournisseur.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	//document.HomeForm.myaction.value='change';
			document.HomeForm.submit();
        }
}

function validateForm2(){
        var exercice = trimAll(document.HomeForm.exercice.options[document.HomeForm.exercice.selectedIndex].value);
		var cantine = trimAll(document.HomeForm.cantine.options[document.HomeForm.cantine.selectedIndex].value);
		var province = trimAll(document.HomeForm.province.options[document.HomeForm.province.selectedIndex].value);
		var msg = '';

		if(exercice == "00") {
        	msg += '- Veuillez sélectionner l\'exercice budgétaire.\n';
        }
		if(cantine == "00") {
        	msg += '- Veuillez sélectionner une cantine.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
        	//document.HomeForm.myaction.value='change';
			document.HomeForm.submit();
        }
}

function fillService(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			//alert(retour);
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('cantine').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbuser.php?do=fillService",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('province').options[document.getElementById('province').selectedIndex].value;
	xhr.send("province="+id);
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
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
          <td height="24" colspan="2" align="right" valign=top><span class="wtext"><?php echo RIGHT_MENU; ?>
	  </td>
      </tr>
          <tr>
            <td height="20" valign="top"><?php echo $menu['Top']; ?>
            <td align="right"> <?php echo LOGOUT; ?>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>
    <tr class="searchBg">
      <td height="21" align="center">

	 <table border="0"cellspacing="0" cellpadding="0">
          <tr>

            <td align="left" class="leftHeader"><?php echo EXBG_MAG; ?></td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
				<td>&nbsp;</td>
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
            <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(185); ?> <?php echo TITLE; ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" height="3"></td>
          </tr>
          <!-- Data Tbale contener -->
        </table></td>
      </tr>
    </table>
      <table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                    <tr>
                      <td colspan=2 align="left" valign="top"><form name="HomeForm" action="dbuser.php?do=change" method="POST">
                          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                            <tr class="header2Bg">
                              <td align="left" valign="top" class="boldText" ><?php echo getlang(263); ?> <font color="#066"><?php echo $_SESSION['GL_USER']['EXERCICE']; ?></font>. <?php echo getlang(264); ?></td>
                            </tr>
                            <tr>
                              <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                                  <tbody>
                                    <tr align="left" valign="top">
                                      <td width="237" align=right valign="middle" class="text"><?php echo getlang(62); ?> :</td>
                                      <td width="343" align="left" class="text"><select name="exercice" class="formStyle" id="exercice" onChange="validateForm();">
                                          <option value="00">[<?php echo getlang(426); ?> <?php echo getlang(62); ?>]</option>
                                          <?php echo $listeExercice; ?>
                                      </select>

									  <input name="myaction" type="hidden" id="myaction" value=''>
									  <input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
									  <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" /></td>
                                    </tr>
									<tr>
									  <td class="text" align="right"><?php echo getlang(150); ?> &nbsp;:&nbsp;</td>
									  <td class="text" align="left"><select name="province" class="formStyle" id="province" onchange="validateForm();">
                                        <option value="00">[<?php echo getlang(150); ?>]</option>
                                        <?php echo $listeProvinceC; ?>
                                      </select></td>
									</tr>
									<tr>
									  <td class="text" align="right"><?php echo getlang(149); ?> &nbsp;:&nbsp;</td>
									  <td class="text" align="left"><select name="cantine" class="formStyle" id="cantine" onchange="validateForm2();">
                                        <option value="00">[<?php echo getlang(149); ?>]</option>
                                        <?php echo $listeService; ?>
                                      </select></td>
									</tr>
									<tr>
									  <td colspan="2" align="left" valign="middle" class="text"><?php echo $msg; ?></td>
								    </tr>
									<tr><td colspan="2" align="left" valign="middle" class="text">
									<?php
if( $_SESSION['GL_USER']['STATUT_EXERCICE'] == 1) {
	if($ilang=='1' && $ilang!='') {echo "<div class=\"errorBox\">NB: L'exercice budg&eacute;taire ".$_SESSION['GL_USER']['EXERCICE']. " est cl&ocirc;tur&eacute. Aucune donnée n'est modifiable</div>";}
	if($ilang=='2' && $ilang!='') {echo "<div class=\"errorBox\">NB: Fiscal year ".$_SESSION['GL_USER']['EXERCICE']. " fenced. Can not modify data</div>";}
	if($ilang=='3' && $ilang!='') {echo "<div class=\"errorBox\">NB: Exercício orçamental ".$_SESSION['GL_USER']['EXERCICE']. " cercado. Nenhum dado Alterar</div>";}

									
									
									}
									?>
									</td></tr>
                                    </table></td>
                            </tr>
                          </table>
                          <p>&nbsp;</p>
                          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                            <tr class="header2Bg">
                              <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(183); ?></td>
                            </tr>
                            <tr>
                              <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                                <tbody>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text" nowrap="nowrap"><?php echo getlang(105); ?>&nbsp;:&nbsp;</td>
                                    <td width="358" align="left" class="text"><input name="nummlle" type="text" disabled="disabled" class="formStyle" id="nummlle" onblur="go();" value="<?php echo  $nummlle; ?>" />
                                      <span class="mandatory" id="msg"></span><span class="mandatory">*</span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(103); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="nom" type="text" disabled="disabled" class="formStyle" id="nom" value="<?php echo  $nom; ?>" />
                                      <span class="mandatory">*</span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(115); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="prenom" type="text" disabled="disabled" class="formStyle" id="prenom" value="<?php echo  $prenom; ?>" />
                                      <span class="mandatory"></span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(153); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><select name="service" id="service" class="formStyle"  disabled="disabled" >
                                      <option value="0"></option>
                                      <?php echo selectService($service);  ?>
                                    </select>
                                      <span class="mandatory">*</span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(165); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="tel" type="text" disabled="disabled" class="formStyle" id="tel" value="<?php echo  $tel; ?>" />
                                      <span class="mandatory">*</span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(5); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="adresse" type="text" disabled="disabled" class="formStyle" id="adresse" value="<?php echo  $adresse; ?>" />
                                      <span class="mandatory"></span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(51); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="email" type="text" disabled="disabled" class="formStyle" id="email" value="<?php echo  $email; ?>" />
                                      <span class="mandatory"></span></td>
                                  </tr>
                                  <tr align="left" valign="top">
                                    <td width="250" align="right" valign="middle" class="text"><?php echo getlang(65); ?>&nbsp;:&nbsp;</td>
                                    <td align="left" class="text"><input name="fonction" type="text" disabled="disabled" class="formStyle" id="fonction" value="<?php echo  $fonction; ?>" />
                                      <span class="mandatory"></span></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                          </table>
                          <p><br>
                            <br>
                          </p>
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
