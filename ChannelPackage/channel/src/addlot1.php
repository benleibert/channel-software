<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

if($_SESSION['GL_USER']['DROIT']['bde_liv']['AJOUT']!=1) header("location:accessinterdit.php?selectedTab=home");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funclot.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = commandesMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);
//Rsest
if(isset($_GET['rst']) && $_GET['rst']==1) {$_SESSION['WHERE']='';}

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bde_liv'];

//Data
(isset($_SESSION['DATA_LOT']['exercice']) 		? $exercice 	= $_SESSION['DATA_LOT']['exercice']: $exercice ='');
(isset($_SESSION['DATA_LOT']['datelivraison']) 	? $datelivraison = $_SESSION['DATA_LOT']['datelivraison']: $datelivraison ='');
(isset($_SESSION['DATA_LOT']['livraison']) 	? $livraison = $_SESSION['DATA_LOT']['livraison']: $livraison ='');
(isset($_SESSION['DATA_LOT']['codelivraison']) 	? $codelivraison = $_SESSION['DATA_LOT']['codelivraison']: $codelivraison ='');
(isset($_SESSION['DATA_LOT']['libelle']) 	? $libelle = $_SESSION['DATA_LOT']['libelle']: $libelle ='');
(isset($_SESSION['DATA_LOT']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_LOT']['nbreLigne']: $nbreLigne ='');

(isset($_SESSION['DATA_LOT']['ligne']) ? $data= $_SESSION['DATA_LOT']['ligne'] : $data=array());

//Ligne
$t= ligneaddLot($nbreLigne, $data);
$ligne = $t[1];
$verif = $t[0];

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

<!-- Begin of JS code  -->
<script type="text/javascript" src="../lib/JQuerySpinBtn.js"></script>

<script type="text/javascript">
	<?php echo $verif; ?>
</script>

<script type="text/javascript" language="javascript">

		// Apply the SpinButton code to the appropriate INPUT elements:
		$(function(){

			$("INPUT.spin-button").SpinButton({min:1});

		});

</script>

<script type="text/javascript">

//Add ligne to
function msgAddLign(){
	document.formadd.myaction.value ="addline";
	document.formadd.submit();

	//window.location.reload() recharger une page
}

function validateForm(){
	var livraison = trimAll(document.formadd.livraison.value);
	var msg = '';

	if(livraison == "") {
      	msg += '- Veuillez sélectionner la livraison concernée.\n';
    }
	if(tousVide()){
      	msg += '- Veuillez saisir les références de lots, date de péremption, quantités des produits.\n';
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
            <?php echo EXBG_MAG; ?> </td>
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
        <td><table width="100%"  border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
              <tr>
                <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(62); ?> -><?php echo getlang(244); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dblot.php?do=add" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Ajouter des lots (Etape n&deg;2)</td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(62); ?> :</td>
                            <td width="358" align="left" class="text"><select name="xexercice" id="xexercice" class="formStyle" readonly="readonly"  disabled="disabled">
                              <option value="0"></option>
                              <?php echo selectExercice($_SESSION['GL_USER']['EXERCICE']); ?>
                            </select>
                              <span class="mandatory" id="msg">*
                                <input name="exercice" type="hidden" id="exercice" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              </span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(45); ?> :</td>
                            <td align="left" class="text"><input name="datelivraison" type="text" class="formStyle" id="datepicker1" value="<?php echo $datelivraison; ?>" readonly="readonly" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(141); ?> :</td>
                            <td align="left" class="text"><input name="codelivraison"  id="codelivraison" type="text" class="formStyle" onblur="go();" value="<?php echo  $codelivraison; ?>"  readonly="readonly"/>
                              <span class="mandatory" id="msg2">
                              <input name="livraison" type="hidden" id="livraison" value="<?php echo $livraison; ?>" />
                              </span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(83); ?> :</td>
                            <td align="left" class="text"><input name="libelle"  id="libelle" type="text" class="formStyle" value="<?php echo  $libelle; ?>" readonly="readonly" />
                              <span class="mandatory">*</span></td>
                          </tr>
                          <tr>
                            <td align="right" valign="middle" class="text"><?php echo getlang(53); ?> :</td>
                            <td align="left" class="text"><input name="nbreLigne" type="text" class="spin-button formStyleFree" id="txtSpin" value="<?php echo $nbreLigne; ?>" size="5" /></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><span class="mandatory">*</span> <?php echo getlang(215); ?></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table><br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" >&nbsp;Lignes de lots</td>
                        </tr>
                        <tr>
                          <td class="text" align="center"><table width="623" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="4" align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="4" align=right valign="middle" class="text">&nbsp;</td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>
                                  <td width="64" align=right valign="middle" nowrap class="text"><div align="center">Réf. Lot</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(42); ?> pérempt.</div></td>
                                 <td width="48" align=center valign="middle" class="text"><div align="center">Qt&eacute; du lot</div></td>

                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="10" class="text" nowrap="nowrap"><?php echo $ligne ; ?>
								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="10"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                                  <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                                  <input name='myaction' type='hidden' id="myaction" />
                                  <input name="rowSelection" type="hidden" id="rowSelection" value="" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td colspan="9">
							      <!-- <input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addBonentree.php?selectedTab=inputs';"> -->
							      <input name="AjouterLign" type="button" class="button" id="AjouterLign"  value='<?php echo getlang(191); ?>' onclick="msgAddLign();" />
							      <input name="Enregistrer" type="button" class="button" id="Enregistrer"  value='<?php echo getlang(189); ?>'  onClick="validateForm();"></td>
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
