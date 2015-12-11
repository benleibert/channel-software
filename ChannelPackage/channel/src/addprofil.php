<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['par_grp']['AJOUT']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcprofil.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_grp'];

//Grp
$ligneDroit = ligneDroitProfil();

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

<script langage="javascript">
function validateForm(){
        var codeprofil = trimAll(document.formadd.codeprofil.value);
        var libprofil = trimAll(document.formadd.libprofil.value);
		var msg = '';

		if(libprofil == '') {
        	msg += '- Veuillez saisir le code du profil.\n';
        }
		if(libprofil == '') {
        	msg += '- Veuillez saisir le libellé du profil.\n';
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
		// On ne fait quelque chose que si on a tout reçu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('nummlle').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbprofil.php?do=check",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeprofil').value;
	xhr.send("code="+id);
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
	<td width="200" valign="top"></td>
	<td width="10"></td>
    <td width="*%" valign="top"></td>
</tr>
</table>
<!--  END Table -->

<table width="100%" height="80%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top"><table width="200" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="5" height="48">&nbsp;</td>
        <td width="180" align="left" valign="top"><table width="200" border="0" cellpadding="1" cellspacing="0">
          <tr>
            <td width="180"><?php echo $leftMenu; ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
    <td width="85%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
      <tr>
        <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> -> <?php echo getlang(178); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><form action="dbprofil.php?do=add" method="post" name="formadd" id="formadd">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
            <tr class="header2Bg">
              <td align="left" valign="top" class="boldText" ><?php echo getlang(11); ?> <?php echo getlang(68); ?></td>
            </tr>
            <tr>
              <td class="text" align="center"><table width="100%" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                <tbody>
                  <tr align="left" valign="top">
                    <td align="right" valign="middle" class="text"><?php echo getlang(32); ?>&nbsp;:&nbsp;</td>
                    <td align="left" class="text"><input name="codeprofil" type="text" class="formStyle" id="codeprofil" onblur="go();" />
                      <span class="mandatory" id="msg2"></span><span class="mandatory">*</span></td>
                  </tr>
                  <tr align="left" valign="top">
                    <td width="30%" align="right" valign="middle" class="text"><?php echo getlang(84); ?>&nbsp;:&nbsp;</td>
                    <td width="358" align="left" class="text"><input name="libprofil" type="text" class="formStyle" id="libprofil"  />
                      <span class="mandatory" id="msg"></span><span class="mandatory">*</span></td>
                  </tr>
                  <tr>
                    <td width="30%"></td>
                    <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                  </tr>
                </tbody>
              </table></td>
            </tr>
          </table>
          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
            <tr class="header2Bg">
              <td align="left" valign="top" class="boldText" ><?php echo getlang(47); ?>                </td>
            </tr>
            <tr>
              <td class="text" align="center"><table width="100%" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                <tbody><tr align="left" valign="top">
                  <td width="30%">&nbsp;</td>
                  <td valign="middle"><span class="boldText"> <label>
                  <input type="checkbox" name="toggleAll" value="checkbox" onclick="doMyAction();"  />
                  <?php echo getlang(1); ?></label></span></td>
                  </tr>
                  <?php echo  $ligneDroit; ?>
                  <tr align="left" valign="top">
                    <td width="30%">&nbsp;</td>
                    <td width="358"><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(190); ?>" onclick="validateForm();" />
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
</table>
</body>
</html>
