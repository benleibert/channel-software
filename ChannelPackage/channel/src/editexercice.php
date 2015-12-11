<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['par_bud']['MODIF']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcexercice.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_bud'];

(isset($_SESSION['DATA_EX']['ID_EXERCICE']) ? $codeexercice = $_SESSION['DATA_EX']['ID_EXERCICE']: $codeexercice ='');
(isset($_SESSION['DATA_EX']['EX_LIBELLE']) ? $exercice = $_SESSION['DATA_EX']['EX_LIBELLE']: $exercice ='');
(isset($_SESSION['DATA_EX']['EX_DATEDEBUT']) ? $datedebut = $_SESSION['DATA_EX']['EX_DATEDEBUT']: $datedebut ='');
(isset($_SESSION['DATA_EX']['EX_DATEFIN']) ? $datefin = $_SESSION['DATA_EX']['EX_DATEFIN']: $datefin ='');
(isset($_SESSION['DATA_EX']['EX_CLOTURE']) ? $cloture = $_SESSION['DATA_EX']['EX_CLOTURE']: $cloture ='');
(isset($_SESSION['DATA_EX']['EX_DATECLOTURE']) ? $datecloture = $_SESSION['DATA_EX']['EX_DATECLOTURE']: $datecloture ='');
($datefin !='0000-00-00' ? $datedebut = frFormat2($datedebut): $datedebut ='');
($datefin !='0000-00-00' ? $datefin = frFormat2($datefin): $datefin ='');
($datefin !='0000-00-00' ? $datecloture = frFormat2($datecloture): $datecloture ='');

($cloture==1 ? $checked= 'checked="checked"' : $checked= '');


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


<!-- Pickdate--------------------------------------  -->
<link type="text/css" href="../lib/themes/base/ui.all.css" rel="stylesheet" />
<link type="text/css" href="../lib/demos.css" rel="stylesheet" />
<script type="text/javascript" src="../lib/ui/ui.core.js"></script>
<script type="text/javascript" src="../lib/ui/ui.datepicker.js"></script>

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

<script type="text/javascript">
function validateForm(){
	var codeexercice = trimAll(document.formadd.codeexercice.value);
	var exercice 	= trimAll(document.formadd.exercice.value);
	var datedebut	= trimAll(document.formadd.datedebut.value);
	var datefin 	= trimAll(document.formadd.datefin.value);
	var msg = '';

	if(codeexercice == '') {
       	msg += '- Veuillez saisir le code exercice.\n';
    }
	if(exercice == "") {
      	msg += '- Veuillez saisir le libellé de l\'exercice.\n';
    }
	if(datedebut == "") {
      	msg += '- Veuillez saisir la date de début.\n';
    }
	if(datefin == "") {
       	msg += '- Veuillez saisir la date de fin.\n';
    }
	if(msg !=''){
		alert(msg);
	}
	else {
		document.formadd.submit();
    }
}

function go(){
	if(document.getElementById('codeexercice').value != document.getElementById('oldcodeexercice').value){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('codeexercice').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbexercice.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeexercice').value;
	xhr.send("codeexercice="+id);
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(447); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbexercice.php?do=update" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(440); ?> <?php echo getlang(62); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text">Code <?php echo getlang(62); ?>&nbsp;:&nbsp;</td>
                            <td width="358" align="left" class="text"><input name="codeexercice" type="text" class="formStyle" id="codeexercice" onblur="go();" value="<?php echo  $codeexercice; ?>" />
                              <span class="mandatory" id="msg">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(62); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="exercice" type="text" class="formStyle" id="exercice" value="<?php echo  $exercice; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(398); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="datedebut" type="text" class="formStyle" id="datepicker1" value="<?php echo  $datedebut; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(399); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="datefin" type="text" class="formStyle" id="datepicker2" value="<?php echo  $datefin; ?>" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(445); ?> :&nbsp;</td>
                            <td align="left" class="text"><input type="checkbox" name="cloture" id="cloture" value="1" <?php echo  $checked; ?> /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(400); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="datecloture" type="text" class="formStyle" id="datepicker3" value="<?php echo  $datecloture; ?>" /></td>
                          </tr>
                          <tr>
                            <td><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name='oldcodeexercice' type='hidden' id="oldcodeexercice" value="<?php echo  $codeexercice; ?>" /></td>
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
