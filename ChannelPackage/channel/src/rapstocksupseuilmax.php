<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcetat.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = rapportMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

$listeService = selectmagasinForProvince($_SESSION['GL_USER']['PROVINCE'], $_SESSION['GL_USER']['MAGASIN']);

$listeService = '';
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
function validateForm(){
	//var categorie 	= trimAll(document.formadd.categorie.options[document.formadd.categorie.selectedIndex].value);
	var datedebut 	= trimAll(document.formadd.datedebut.value);
	var datefin 	= trimAll(document.formadd.datefin.value);
	//var province 	= trimAll(document.formadd.province.options[document.formadd.province.selectedIndex].value);
	//var service 	= trimAll(document.formadd.service.options[document.formadd.service.selectedIndex].value);
	var msg = '';

	if(datedebut==''){
      	msg += '- Veuillez saisir la date début.\n';
    }
	else if(!checkDate(document.formadd.datedebut)){
      	msg += '- Date début incorrecte.\n';
    }
	if(datefin==''){
      	msg += '- Veuillez saisir la date fin.\n';
    }
	else if(!checkDate(document.formadd.datefin)){
      	msg += '- Date fin incorrecte.\n';
    }
	if (!CompareDate(datedebut, document.formadd.debutexercice.value, '>=') || !CompareDate(datedebut, document.formadd.finexercice.value, '<=')) {msg += '- Date début hors exercice budegétaire ['+document.formadd.debutexercice.value+' - '+document.formadd.finexercice.value+'].\n';				
	}
	if (!CompareDate(datefin, document.formadd.debutexercice.value, '>=') || !CompareDate(datefin, document.formadd.finexercice.value, '<=')) {msg += '- Date fin hors exercice budegétaire ['+document.formadd.debutexercice.value+' - '+document.formadd.finexercice.value+'].\n';				
	}				
	if(province == "0") {
      	msg += '- Veuillez sélectionner le site fournisseur.\n';
    }
	

	if(msg !=''){
		alert(msg);
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



function fillService(){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('service').innerHTML = retour;
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbetat.php?do=fillService",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('province').options[document.getElementById('province').selectedIndex].value;
	xhr.send("code="+id);
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(415); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbetat.php?do=rapstocksupseuilmax" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(416); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="232" align="right" valign="middle" class="text"><?php echo getlang(62); ?> : </td>
                            <td width="348" align="left" class="text"><select name="xexercice" id="xexercice" class="formStyle" readonly="readonly"  disabled="disabled">
                              <option value="0"></option>
                              <?php echo selectExercice($_SESSION['GL_USER']['EXERCICE']); ?>
                              </select>
                              <input name="exercice" type="hidden" id="exercice" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <span class="mandatory" >*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td rowspan="2" align="right" valign="middle" class="text"><?php echo getlang(43); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="datedebut" type="text" class="formStyle" id="datepicker3" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                              <span class="mandatory" >*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="left" class="text"><input name="datefin" type="text" class="formStyle" id="datepicker2" value="<?php echo date('d/m/Y'); ?>" />
                              <span class="mandatory" >*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(102); ?> :</td>
                            <td align="left" class="text"><select name="region" id="region" class="formStyle" onchange="fillProvince();">
                              <option value="0"><?php echo getlang(232); ?></option>
                              <?php echo selectRegion(''); ?>
                            </select></td>
                          </tr>
                          <tr>
                            <td class="text" align="right"><?php echo getlang(150); ?> &nbsp;:&nbsp;</td>
                            <td class="text" align="left"><select name="province" class="formStyle" id="province" onchange="fillService();">
                              <option value="0">[<?php echo getlang(150); ?>]</option>
                             </select></td>
                          </tr>
                          <tr>
                            <td class="text" align="right"><?php echo getlang(149); ?> &nbsp;:&nbsp;</td>
                            <td class="text" align="left"><select name="service" class="formStyle" id="service" >
                              <option value="0">[<?php echo getlang(149); ?>]</option>
                            </select></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="232"><input name='debutexercice' type='hidden' id="debutexercice" value="<?php echo $_SESSION['GL_USER']['DEBUT_EXERCICE']; ?>" />
                              <input name='finexercice' type='hidden' id="finexercice"  value="<?php echo $_SESSION['GL_USER']['FIN_EXERCICE']; ?>" /></td>
                            <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(196); ?>" onclick="validateForm();" />
                              <input name="Retablir" type="reset" class="button" id="Retablir"  value='<?php echo getlang(193); ?>' /></td>
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
