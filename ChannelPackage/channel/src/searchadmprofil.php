<?php
session_start();
if($_SESSION['ADMIN']['IDSESSION'] != session_id())header("location:dbcompte.php?do=close");

//PHP functions librairy
require_once('../lib/phpfuncLib.php');	//All commun functions
require_once('menus.php');			//Menu functions
require_once('funcprofil.php');		//Profil functions

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['ADMIN']['DROIT']);

//Left Menu
$leftMenu = parametrageMenus($selectedTab , $_SESSION['ADMIN']['DROIT']);


//Grp
$ligneDroit = ligneDroitProfil();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo APP_TITLE; ?></title>

<!-- Begin of CSS code  -->
<link href="../css/neutralcss.css" rel="stylesheet" type="text/css" />
<link href="../css/defaultcss.css" rel="stylesheet" type="text/css">

<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jslib.js"></script>
<script type="text/javascript" src="../lib/jsfuncLib.js"></script>

<!-- Perso js code -->
<script type="text/javascript">
function validateForm(){
        var libprofil = trimAll(document.formadd.libprofil.value);
		var msg = '';

		if(libprofil == '') {
        	msg += '- Veuillez saisir le critère de <?php echo getlang(139); ?>.\n';
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
	xhr.open("POST","dbpersonnel.php?do=check",true);
	// ne pas oublier ça pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('nmlle').value;
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
		document.write('<table id=maintable width=100%  border=0  cellspacing=0 cellpadding=0 >');
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

<!--  BEGIN Table -->
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="200" rowspan=2 align="left" valign="top"><img src="../images/logoYaaba.gif" hspace=2 vspace=0 align="absmiddle"></td>
      <td height="24" colspan="2" align="right" valign=baseline><?php echo RIGHT_MENU; ?></td>
    </tr>
    <tr> 
       <td height="20" valign="top"><?php echo $menu['Top']; ?>
       </td>
       <td align="right">
           <?php echo LOGOUT; ?>&nbsp;</td>
        </tr>
      </table>
    </td>
</tr>
    
    <tr class="searchBg">
      <td height="21" align="left" valign="middle"><?php echo $menu['Sub']; ?> <?php echo INFO; ?></td>
    </tr>
    <tr><td>&nbsp;</td>
  </table>
</td>
  </tr>
</table>
<!--  END Table -->

<!--  BEGIN Table -->
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
        <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(178); ?> -> <?php echo getlang(110); ?></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><form action="../src/admprofil.php?selectedTab=par&amp;do=search" method="post" name="formadd" id="formadd">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
            <tr class="header2Bg">
              <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(139); ?> un profil</td>
            </tr>
            <tr>
              <td class="text" align="center"><table width="100%" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                <tbody>
                  <tr align="left" valign="top">
                    <td width="30%" align="right" valign="middle" class="text"><?php echo getlang(84); ?>&nbsp;:&nbsp;</td>
                    <td width="358" align="left" class="text"><input name="libprofil" type="text" class="formStyle" id="libprofil"  />
                      <span class="mandatory" id="msg"></span></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr align="left" valign="top">
                    <td>&nbsp;</td>
                    <td><input name="Suivant2" type="button" class="button" id="Suivant2"  value="<?php echo getlang(139); ?>" onclick="validateForm();" />
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
</table>
</body>
</html>
