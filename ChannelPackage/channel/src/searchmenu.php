<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcmenu.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_men'];
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
		
		var codemenu = trimAll(document.formadd.codemenu.value);
		var libmenu = trimAll(document.formadd.libmenu.value);
		var msg = '';

		if(codemenu == '' && libmenu == '') {
        	msg += '- Veuillez saisir le critère de <?php echo getlang(139); ?>.\n';
        }
		
		if(msg !=''){
			alert(msg);
		}
		else {
			document.formadd.submit();
        }
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
        <td height="20" bgcolor="#FFCC66" class="leftHeader">Autres paramètres &rarr; Fonctionnalité</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="top"><form action="../src/menu.php?selectedTab=par&amp;do=search" method="post" name="formadd" id="formadd">
          <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
            <tr class="header2Bg">
              <td align="left" valign="top" class="boldText" >&nbsp;<?php echo getlang(139); ?> une fonctionnalité</td>
            </tr>
            <tr>
              <td class="text" align="center"><table width="100%" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                <tbody>
                  <tr align="left" valign="top">
                    <td width="30%" align="right" valign="middle" class="text">Code menu&nbsp;:&nbsp;</td>
                    <td width="358" align="left" class="text"><input name="codemenu" type="text" class="formStyle" id="codemenu" />
                      <span class="mandatory" id="msg"></span></td>
                  </tr>
                  <tr align="left" valign="top">
                    <td width="30%" align="right" valign="middle" class="text">Libellé menu&nbsp;:&nbsp;</td>
                    <td align="left" class="text"><input name="libmenu" type="text" class="formStyle" id="libmenu" /></td>
                  </tr>
                  <tr>
                    <td width="30%"></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr align="left" valign="top">
                    <td width="30%">&nbsp;</td>
                    <td><input name="Suivant" type="button" class="button" id="Suivant"  value="<?php echo getlang(139); ?>" onclick="validateForm();" />
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
