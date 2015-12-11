<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcproduit.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = parametersMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['par_prd'];

$ligne= ligneMajPrixProduit();

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
        
			document.formadd.submit();
}

function go(){
	if(document.getElementById('codeproduit').value != document.getElementById('oldcodeproduit').value){
	var xhr = getXhr();
	// On défini ce qu'on va faire quand on aura la réponse
	xhr.onreadystatechange = function(){
		// On ne fait quelque chose que si on a tout reéu et que le serveur est ok
		if(xhr.readyState == 4 && xhr.status == 200){
			retour = xhr.responseText;
			// On se sert de innerHTML pour rajouter les options a la liste
			document.getElementById('msg').innerHTML = retour;
			if(retour !='') document.getElementById('codeproduit').focus();
		}
	}

	// Ici on va voir comment faire du post
	xhr.open("POST","dbproduit.php?do=check",true);
	// ne pas oublier éa pour le post
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	// ne pas oublier de poster les arguments
	// ici, l'id de l'auteur
	id = document.getElementById('codeproduit').value;
	xhr.send("codeproduit="+id);
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(106); ?> -> <?php echo getlang(386); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbproduit.php?do=updateprix" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(386); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="100%" border="0" cellpadding="2" cellspacing="1" class="tableBorder">
                        <!-- Begin Table Header -->
                        <tr class="header2">
                          <td height="25" colspan="4" align="right" class="header2Bg">Site <?php echo getlang(258); ?> --&gt;</td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(221); ?></td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(262); ?></td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(220); ?></td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(221); ?></td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(262); ?></td>
                          <td colspan="2" align="center" valign="middle" class="header2Bg"><?php echo getlang(220); ?></td>
                          <td height="25" align="left" valign="middle" class="header2Bg">&lt;-- Site <?php echo getlang(259); ?></td>
                        </tr>
                        <tr class="header2">
                          <td width="3%" class="header2Bg">N°</td>
                          <td width="7%" height="25" align="center" valign="middle" class="header2Bg">Code </td>
                          <td width="14%" height="25" align="left" valign="middle" class="header2Bg">&nbsp;&nbsp;<?php echo getlang(116); ?></td>
                          <td width="7%" align="center" valign="middle" class="header2Bg"><?php echo getlang(204); ?></td>
                          <td width="4%" align="center" valign="middle" class="header2Bg">PA</td>
                          <td width="5%" align="center" valign="middle" class="header2Bg" nowrap="nowrap">MAJ PA</td>
                          <td width="4%" align="center" valign="middle" class="header2Bg">PR</td>
                          <td width="5%" align="center" valign="middle" class="header2Bg" nowrap="nowrap">MAJ PR</td>
                          <td width="4%" align="center" valign="middle" class="header2Bg">PV</td>
                          <td width="5%" align="center" valign="middle" class="header2Bg" nowrap="nowrap">MAJ PV</td>
                          <td align="center" valign="middle" class="header2Bg">PA</td>
                          <td width="5%" align="center" valign="middle" nowrap="nowrap" class="header2Bg">MAJ PA</td>
                          <td align="center" valign="middle" class="header2Bg">PR</td>
                          <td width="5%" align="center" valign="middle" nowrap="nowrap" class="header2Bg">MAJ PR</td>
                          <td align="center" valign="middle" class="header2Bg">PV</td>
                          <td width="5%" align="center" valign="middle" nowrap="nowrap" class="header2Bg">MAJ PV</td>
                          <td width="14%" height="25" align="left" valign="middle" class="header2Bg">&nbsp;&nbsp;<?php echo getlang(384); ?> </td>
                        </tr>
                        <!-- Begin Table row -->
                        <?php echo $ligne; ?>
                        <!-- End Table row -->
                      </table>                        <br />
                      <table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <!--
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Ration journali&egrave;re&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="ration" type="text" class="formStyle" id="ration" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Prix du plat&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="prixplat" type="text" class="formStyle" id="prixplat" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          -->
                          <tr align="left" valign="top">
                            <td><input name="Suivant2" type="button" class="button" id="Suivant2"  value="<?php echo getlang(190); ?>" onclick="validateForm();" />
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
