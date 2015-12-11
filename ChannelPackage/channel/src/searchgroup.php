<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcgroup.php');

unset($_SESSION['WHERE']);
$droit = getDroit('GRP_PERSONNEL ,GRP_UTILISATEUR, GRP_GROUPE, GRP_LOG,	GRP_CATEGORIE, GRP_PRODUIT,	GRP_CONDITIONNEMENT, GRP_USTENSILE,	GRP_UNITE, GRP_CONVERSION, GRP_BAREME, GRP_REGION, GRP_PROVINCE, GRP_TYPESERVICE, GRP_SERVICE, GRP_MAGASIN, GRP_FOURNISSEUR, GRP_TYPEBENEFICIAIRE, GRP_BENEFICIAIRE, GRP_EXERCICE, GRP_TYPEDOTATION, GRP_PARAMETRE, GRP_DB', $_SESSION['GL_USER']['GROUPE']);

$droitTOPMENUS = getDroitTOPMENUS( $_SESSION['GL_USER']['GROUPE']);

$droitMAJ = getDroitMAJ('GRP_GROUPE', $_SESSION['GL_USER']['GROUPE']);

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
        var nom = trimAll(document.formadd.nom.value);
		var msg = '';

		if(nom == '') {
        	msg += '- Veuillez saisir le nom du groupe.\n';
        }
		if(msg !=''){
			alert(msg);
		}
		else {
			document.formadd.submit();
        }
}

function  doCdeLivr(){
	if(document.formadd.tousCDE_LIV.checked==true) {
	document.formadd.visibleCDE.checked=true;
	document.formadd.visibleLIV.checked=true;

	document.formadd.ajoutCDE.checked=true;
	document.formadd.ajoutLIV.checked=true;

	document.formadd.modifCDE.checked=true;
	document.formadd.modifLIV.checked=true;

	document.formadd.supprCDE.checked=true;
	document.formadd.supprLIV.checked=true;

	document.formadd.validCDE.checked=true;
	document.formadd.validLIV.checked=true;

	document.formadd.visibleCDE_LIV.checked=true;
	}
	else{
	document.formadd.visibleCDE.checked=false;
	document.formadd.visibleLIV.checked=false;

	document.formadd.ajoutCDE.checked=false;
	document.formadd.ajoutLIV.checked=false;

	document.formadd.modifCDE.checked=false;
	document.formadd.modifLIV.checked=false;

	document.formadd.supprCDE.checked=false;
	document.formadd.supprLIV.checked=false;

	document.formadd.validCDE.checked=false;
	document.formadd.validLIV.checked=false;

	document.formadd.visibleCDE_LIV.checked=false;
	}

}

function  doPrgRev(){
	if(document.formadd.tousPRG_REV.checked==true) {
	document.formadd.visiblePRG.checked=true;
	document.formadd.visibleREV.checked=true;

	document.formadd.ajoutPRG.checked=true;
	document.formadd.ajoutREV.checked=true;

	document.formadd.modifPRG.checked=true;
	document.formadd.modifREV.checked=true;

	document.formadd.supprPRG.checked=true;
	document.formadd.supprREV.checked=true;

	document.formadd.validPRG.checked=true;
	document.formadd.validREV.checked=true;

	document.formadd.visiblePRG_REV.checked=true;
	}
	else{
	document.formadd.visiblePRG.checked=false;
	document.formadd.visibleREV.checked=false;

	document.formadd.ajoutPRG.checked=false;
	document.formadd.ajoutREV.checked=false;

	document.formadd.modifPRG.checked=false;
	document.formadd.modifREV.checked=false;

	document.formadd.supprPRG.checked=false;
	document.formadd.supprREV.checked=false;

	document.formadd.validPRG.checked=false;
	document.formadd.validREV.checked=false;

	document.formadd.visiblePRG_REV.checked=false;
	}

}

function doMvt(){
	if(document.formadd.tousMVT.checked==true) {
	document.formadd.visibleSTO.checked=true;
	document.formadd.visibleDEC.checked=true;
	document.formadd.visibleDOT.checked=true;
	document.formadd.visibleTRA.checked=true;
	document.formadd.visibleREP.checked=true;

	document.formadd.ajoutSTO.checked=true;
	document.formadd.ajoutDEC.checked=true;
	document.formadd.ajoutDOT.checked=true;
	document.formadd.ajoutTRA.checked=true;
	document.formadd.ajoutREP.checked=true;

	document.formadd.modifSTO.checked=true;
	document.formadd.modifDEC.checked=true;
	document.formadd.modifDOT.checked=true;
	document.formadd.modifTRA.checked=true;
	document.formadd.modifREP.checked=true;

	document.formadd.supprSTO.checked=true;
	document.formadd.supprDEC.checked=true;
	document.formadd.supprDOT.checked=true;
	document.formadd.supprTRA.checked=true;
	document.formadd.supprREP.checked=true;

	document.formadd.validSTO.checked=true;
	document.formadd.validDEC.checked=true;
	document.formadd.validDOT.checked=true;
	document.formadd.validTRA.checked=true;
	document.formadd.validREP.checked=true;

	document.formadd.visibleMVT.checked=true;
	}
	else{
	document.formadd.visibleSTO.checked=false;
	document.formadd.visibleDEC.checked=false;
	document.formadd.visibleDOT.checked=false;
	document.formadd.visibleTRA.checked=false;
	document.formadd.visibleREP.checked=false;

	document.formadd.ajoutSTO.checked=false;
	document.formadd.ajoutDEC.checked=false;
	document.formadd.ajoutDOT.checked=false;
	document.formadd.ajoutTRA.checked=false;
	document.formadd.ajoutREP.checked=false;

	document.formadd.modifSTO.checked=false;
	document.formadd.modifDEC.checked=false;
	document.formadd.modifDOT.checked=false;
	document.formadd.modifTRA.checked=false;
	document.formadd.modifREP.checked=false;

	document.formadd.supprSTO.checked=false;
	document.formadd.supprDEC.checked=false;
	document.formadd.supprDOT.checked=false;
	document.formadd.supprTRA.checked=false;
	document.formadd.supprREP.checked=false;

	document.formadd.validSTO.checked=false;
	document.formadd.validDEC.checked=false;
	document.formadd.validDOT.checked=false;
	document.formadd.validTRA.checked=false;
	document.formadd.validREP.checked=false;

	document.formadd.visibleMVT.checked=false;
	}

}

function doPar(){
	if(document.formadd.tousPAR.checked==true) {
	document.formadd.visiblePER.checked=true;
	document.formadd.visibleUTI.checked=true;
	document.formadd.visibleGRP.checked=true;
	document.formadd.visibleLOG.checked=true;
	document.formadd.visibleCAT.checked=true;
	document.formadd.visiblePRD.checked=true;
	document.formadd.visibleCND.checked=true;
	document.formadd.visibleUST.checked=true;
	document.formadd.visibleUNI.checked=true;
	document.formadd.visibleTCO.checked=true;
	document.formadd.visibleCND.checked=true;
	document.formadd.visibleBAR.checked=true;
	document.formadd.visibleTLO.checked=true;
	document.formadd.visibleLOC.checked=true;
	document.formadd.visibleTSE.checked=true;
	document.formadd.visibleSER.checked=true;
	document.formadd.visibleTBE.checked=true;
	document.formadd.visibleBEN.checked=true;
	document.formadd.visibleFOU.checked=true;
	document.formadd.visibleEXE.checked=true;
	document.formadd.visibleTDO.checked=true;
	document.formadd.visiblePGL.checked=true;
	document.formadd.visibleMAG.checked=true;

	document.formadd.ajoutPER.checked=true;
	document.formadd.ajoutUTI.checked=true;
	document.formadd.ajoutGRP.checked=true;
	document.formadd.ajoutLOG.checked=true;
	document.formadd.ajoutCAT.checked=true;
	document.formadd.ajoutPRD.checked=true;
	document.formadd.ajoutCND.checked=true;
	document.formadd.ajoutUST.checked=true;
	document.formadd.ajoutUNI.checked=true;
	document.formadd.ajoutTCO.checked=true;
	document.formadd.ajoutCND.checked=true;
	document.formadd.ajoutBAR.checked=true;
	document.formadd.ajoutTLO.checked=true;
	document.formadd.ajoutLOC.checked=true;
	document.formadd.ajoutTSE.checked=true;
	document.formadd.ajoutSER.checked=true;
	document.formadd.ajoutTBE.checked=true;
	document.formadd.ajoutBEN.checked=true;
	document.formadd.ajoutFOU.checked=true;
	document.formadd.ajoutEXE.checked=true;
	document.formadd.ajoutTDO.checked=true;
	document.formadd.ajoutPGL.checked=true;
	document.formadd.ajoutMAG.checked=true;

	document.formadd.modifPER.checked=true;
	document.formadd.modifUTI.checked=true;
	document.formadd.modifGRP.checked=true;
	document.formadd.modifLOG.checked=true;
	document.formadd.modifCAT.checked=true;
	document.formadd.modifPRD.checked=true;
	document.formadd.modifCND.checked=true;
	document.formadd.modifUST.checked=true;
	document.formadd.modifUNI.checked=true;
	document.formadd.modifTCO.checked=true;
	document.formadd.modifCND.checked=true;
	document.formadd.modifBAR.checked=true;
	document.formadd.modifTLO.checked=true;
	document.formadd.modifLOC.checked=true;
	document.formadd.modifTSE.checked=true;
	document.formadd.modifSER.checked=true;
	document.formadd.modifTBE.checked=true;
	document.formadd.modifBEN.checked=true;
	document.formadd.modifFOU.checked=true;
	document.formadd.modifEXE.checked=true;
	document.formadd.modifTDO.checked=true;
	document.formadd.modifPGL.checked=true;
	document.formadd.modifMAG.checked=true;

	document.formadd.supprPER.checked=true;
	document.formadd.supprUTI.checked=true;
	document.formadd.supprGRP.checked=true;
	document.formadd.supprLOG.checked=true;
	document.formadd.supprCAT.checked=true;
	document.formadd.supprPRD.checked=true;
	document.formadd.supprCND.checked=true;
	document.formadd.supprUST.checked=true;
	document.formadd.supprUNI.checked=true;
	document.formadd.supprTCO.checked=true;
	document.formadd.supprCND.checked=true;
	document.formadd.supprBAR.checked=true;
	document.formadd.supprTLO.checked=true;
	document.formadd.supprLOC.checked=true;
	document.formadd.supprTSE.checked=true;
	document.formadd.supprSER.checked=true;
	document.formadd.supprTBE.checked=true;
	document.formadd.supprBEN.checked=true;
	document.formadd.supprFOU.checked=true;
	document.formadd.supprEXE.checked=true;
	document.formadd.supprTDO.checked=true;
	document.formadd.supprPGL.checked=true;
	document.formadd.supprMAG.checked=true;

	document.formadd.visiblePAR.checked=true;
	}
	else{
	document.formadd.visiblePER.checked=false;
	document.formadd.visibleUTI.checked=false;
	document.formadd.visibleGRP.checked=false;
	document.formadd.visibleLOG.checked=false;
	document.formadd.visibleCAT.checked=false;
	document.formadd.visiblePRD.checked=false;
	document.formadd.visibleCND.checked=false;
	document.formadd.visibleUST.checked=false;
	document.formadd.visibleUNI.checked=false;
	document.formadd.visibleTCO.checked=false;
	document.formadd.visibleCND.checked=false;
	document.formadd.visibleBAR.checked=false;
	document.formadd.visibleTLO.checked=false;
	document.formadd.visibleLOC.checked=false;
	document.formadd.visibleTSE.checked=false;
	document.formadd.visibleSER.checked=false;
	document.formadd.visibleTBE.checked=false;
	document.formadd.visibleBEN.checked=false;
	document.formadd.visibleFOU.checked=false;
	document.formadd.visibleEXE.checked=false;
	document.formadd.visibleTDO.checked=false;
	document.formadd.visiblePGL.checked=false;
	document.formadd.visibleMAG.checked=false;

	document.formadd.ajoutPER.checked=false;
	document.formadd.ajoutUTI.checked=false;
	document.formadd.ajoutGRP.checked=false;
	document.formadd.ajoutLOG.checked=false;
	document.formadd.ajoutCAT.checked=false;
	document.formadd.ajoutPRD.checked=false;
	document.formadd.ajoutCND.checked=false;
	document.formadd.ajoutUST.checked=false;
	document.formadd.ajoutUNI.checked=false;
	document.formadd.ajoutTCO.checked=false;
	document.formadd.ajoutCND.checked=false;
	document.formadd.ajoutBAR.checked=false;
	document.formadd.ajoutTLO.checked=false;
	document.formadd.ajoutLOC.checked=false;
	document.formadd.ajoutTSE.checked=false;
	document.formadd.ajoutSER.checked=false;
	document.formadd.ajoutTBE.checked=false;
	document.formadd.ajoutBEN.checked=false;
	document.formadd.ajoutFOU.checked=false;
	document.formadd.ajoutEXE.checked=false;
	document.formadd.ajoutTDO.checked=false;
	document.formadd.ajoutPGL.checked=false;
	document.formadd.ajoutMAG.checked=false;

	document.formadd.modifPER.checked=false;
	document.formadd.modifUTI.checked=false;
	document.formadd.modifGRP.checked=false;
	document.formadd.modifLOG.checked=false;
	document.formadd.modifCAT.checked=false;
	document.formadd.modifPRD.checked=false;
	document.formadd.modifCND.checked=false;
	document.formadd.modifUST.checked=false;
	document.formadd.modifUNI.checked=false;
	document.formadd.modifTCO.checked=false;
	document.formadd.modifCND.checked=false;
	document.formadd.modifBAR.checked=false;
	document.formadd.modifTLO.checked=false;
	document.formadd.modifLOC.checked=false;
	document.formadd.modifTSE.checked=false;
	document.formadd.modifSER.checked=false;
	document.formadd.modifTBE.checked=false;
	document.formadd.modifBEN.checked=false;
	document.formadd.modifFOU.checked=false;
	document.formadd.modifEXE.checked=false;
	document.formadd.modifTDO.checked=false;
	document.formadd.modifPGL.checked=false;
	document.formadd.modifMAG.checked=false;

	document.formadd.supprPER.checked=false;
	document.formadd.supprUTI.checked=false;
	document.formadd.supprGRP.checked=false;
	document.formadd.supprLOG.checked=false;
	document.formadd.supprCAT.checked=false;
	document.formadd.supprPRD.checked=false;
	document.formadd.supprCND.checked=false;
	document.formadd.supprUST.checked=false;
	document.formadd.supprUNI.checked=false;
	document.formadd.supprTCO.checked=false;
	document.formadd.supprCND.checked=false;
	document.formadd.supprBAR.checked=false;
	document.formadd.supprTLO.checked=false;
	document.formadd.supprLOC.checked=false;
	document.formadd.supprTSE.checked=false;
	document.formadd.supprSER.checked=false;
	document.formadd.supprTBE.checked=false;
	document.formadd.supprBEN.checked=false;
	document.formadd.supprFOU.checked=false;
	document.formadd.supprEXE.checked=false;
	document.formadd.supprTDO.checked=false;
	document.formadd.supprPGL.checked=false;
	document.formadd.supprMAG.checked=false;

	document.formadd.visiblePAR.checked=false;
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
                <td width="43" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(450); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="group.php?selectedTab=par&amp;do=search" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(139); ?> <?php echo getlang(68); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="600" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text"<?php echo getlang(68); ?>&nbsp;:&nbsp;</td>
                            <td width="334" align="left" class="text"><input name="nom" type="text" class="formStyle" id="nom" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr>
                            <td colspan="2"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name="myaction" type="hidden" id="myaction" value="" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246">&nbsp;</td>
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
