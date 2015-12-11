<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcdeclassement.php');

//Top Menu
$selectedTab = $_GET['selectedTab'];
$menu = topMenus($selectedTab,$_SESSION['GL_USER']['DROIT']);

//Left Menu
$leftMenu = bonsortieMenus($selectedTab , $_SESSION['GL_USER']['DROIT']);

//DOIT MAJ
$droitMAJ = $_SESSION['GL_USER']['DROIT']['bds_dec'];

//Data
(isset($_SESSION['DATA_DEC']['xid']) 		? $xid 	= $_SESSION['DATA_DEC']['xid']: $xid ='');
(isset($_SESSION['DATA_DEC']['exercice']) 		? $exercice 	= $_SESSION['DATA_DEC']['exercice']: $exercice ='');
(isset($_SESSION['DATA_DEC']['datedeclassement']) 	? $datedeclassement = $_SESSION['DATA_DEC']['datedeclassement']: $datedeclassement ='');
(isset($_SESSION['DATA_DEC']['refdeclassement']) 	? $refdeclassement = $_SESSION['DATA_DEC']['refdeclassement']: $refdeclassement ='');
(isset($_SESSION['DATA_DEC']['statut']) 	? $statut 	= $_SESSION['DATA_DEC']['statut']: $statut ='');
(isset($_SESSION['DATA_DEC']['raison']) 	? $raison	= $_SESSION['DATA_DEC']['raison']: $raison ='');
(isset($_SESSION['DATA_DEC']['cabinet']) 	? $cabinet	= $_SESSION['DATA_DEC']['cabinet']: $cabinet ='');
(isset($_SESSION['DATA_DEC']['refrapport']) 	? $refrapport	= $_SESSION['DATA_DEC']['refrapport']: $refrapport ='');
(isset($_SESSION['DATA_DEC']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_DEC']['nbreLigne']: $nbreLigne ='');

(isset($_SESSION['DATA_DEC']['nbreLigne2']) 		? $nbreLigne2 	= $_SESSION['DATA_DEC']['nbreLigne2']: $nbreLigne2 ='');

($statut==1 ? $checked = 'checked="checked"' : $checked ='');

(isset($_SESSION['DATA_DEC']['ligne']) ? $data= $_SESSION['DATA_DEC']['ligne'] : $data=array());

(isset($_SESSION['DATA_DEC']['journal']) ? $data2= $_SESSION['DATA_DEC']['journal'] : $data2=array());

//Ligne
$ligne = lignedetailDeclassement($nbreLigne, $data);

//Ligne
$Journal = lignejournalDeclassement($nbreLigne2,$data2);

//Annulation
$Annuler = sousMenuAnnuler($statut , $tab='bds', $droitMAJ);

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
function msgAnnul(){
	var ret;
	ret = confirm('Veuillez confirmer l\'annulation?');
	if (ret) {
		document.formadd.myaction.value="annul";
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
                <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(271); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbdeclassement.php" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(245); ?> -> <?php echo getlang(111); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="200" align="right" valign="middle" class="text"><?php echo getlang(62); ?> : </td>
                            <td width="358" align="left" class="text"><select name="xexercice" id="xexercice" class="formStyle" readonly="readonly"  disabled="disabled">
                              <option value="0"></option>
                              <?php echo selectExercice($_SESSION['GL_USER']['EXERCICE']); ?>
                            </select>
                              <span class="mandatory">
                                <input name="exercice" type="hidden" id="exercice" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              </span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(45); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="datedeclassement" type="text" class="formStyle" id="datepicker1" readonly="readonly" value="<?php echo date('d/m/Y'); ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(274); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="refdeclassement" type="text" class="formStyle" id="refdeclassement" readonly="readonly" value="<?php echo $refdeclassement; ?>" onblur="go();"  />
                              <span class="mandatory" id="msg"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(99); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="raison" type="text" class="formStyle" readonly="readonly" id="raison" value="<?php echo $raison; ?>" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(276); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="cabinet" type="text" class="formStyle" id="cabinet" readonly="readonly" value="<?php echo $cabinet; ?>"  /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text"><?php echo getlang(275); ?>&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><input name="refrapport" type="text" class="formStyle" id="refrapport" readonly="readonly" value="<?php echo $refrapport; ?>"  /></td>
                          </tr>
                          <tr>
                            <td align="right" valign="middle" class="text"><?php echo getlang(279); ?> :&nbsp;</td>
                            <td align="left" class="text"><input name="statut" type="checkbox" id="statut" value="1" <?php echo  $checked; ?> disabled="disabled" /></td>
                          </tr>
                        </tbody>
                      </table></td>
                    </tr>
                  </table><br />
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                        <tr class="header2Bg">
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(280); ?></td>
                          <td align="left" valign="top" class="boldText" ><?php echo getlang(251); ?></td>
                        </tr>
                        <tr>
                          <td align="center" valign="top" class="text"><table width="500" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=right valign="middle" class="text">&nbsp;</td>
                                 <td width="64" align=right valign="middle" nowrap class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                  <td width="264" align=right valign="middle" class="text"><div align="left"><?php echo getlang(199); ?></div></td>

                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(200); ?> <?php echo getlang(249); ?></div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(220); ?></div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center">Total</div></td>
                                  <td width="48" align=center valign="middle" class="text"><div align="center">Réf. lot</div></td>
								  <td width="48" align=center valign="middle" class="text"><div align="center"><?php echo getlang(226); ?></div></td>
                                  </tr>
                                <tr align="left" valign="middle">
                                  <td colspan="10" class="text" nowrap="nowrap"><?php echo $ligne ; ?>
								  </td>
                                  </tr>
                              	<tr>
                                <td colspan="10"><input name='myaction' type='hidden' id="myaction">
								<input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>">
						<input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>">
						
								<input name="xid" type='hidden' id="xid" value="<?php echo $xid; ?>" />
								<input name="oldrefdeclassement" type='hidden' id="oldrefdeclassement" value="<?php echo $refdeclassement; ?>" /></td>
                              </tr>
							  <tr align="left" valign="top">
							    <td width="4">&nbsp;</td>
							    <td colspan="9">
							      <!-- <input name="Precedent" type="button" class="button" id="Precedent"  value='&lt;&lt; Pr&eacute;c&eacute;dent' onClick="javascript:window.location.href='addBonentree.php?selectedTab=inputs';"> -->
							      <input name="Enregistrer" type="button" class="button" id="Enregistrer"  value='<?php echo getlang(187); ?>'  onClick="OpenBigWin('printdeclassement.php','');"> </td>
  								</tr>
                          </table></td>
                          <td align="center" valign="top" class="text"><table width="400" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                            <tbody>
                              <tr align="left" valign="top" nowrap="nowrap">
                                <td width="4" align="right" valign="middle" class="text">&nbsp;</td>
                                <td width="64" align="right" valign="middle" nowrap="nowrap" class="text"><div align="center"><?php echo getlang(257); ?> </div></td>
                                <td width="48" align="center" valign="middle" class="text"><div align="center"><?php echo getlang(200); ?> <?php echo getlang(249); ?></div></td>
                                <td width="48" align="center" valign="middle" class="text"><div align="center"><?php echo getlang(204); ?></div></td>
                              </tr>
                              <tr align="left" valign="middle">
                                <td colspan="5" class="text" nowrap="nowrap"><?php echo $Journal ; ?></td>
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
