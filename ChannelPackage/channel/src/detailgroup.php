<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");

require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('menus.php');
require_once('funcgroup.php');

$droit = getDroit('GRP_PERSONNEL ,GRP_UTILISATEUR, GRP_GROUPE, GRP_LOG,	GRP_CATEGORIE, GRP_PRODUIT,	GRP_CONDITIONNEMENT, GRP_USTENSILE,	GRP_UNITE, GRP_CONVERSION, GRP_BAREME, GRP_REGION, GRP_PROVINCE, GRP_TYPESERVICE, GRP_SERVICE, GRP_MAGASIN, GRP_FOURNISSEUR, GRP_TYPEBENEFICIAIRE, GRP_BENEFICIAIRE, GRP_EXERCICE, GRP_TYPEDOTATION, GRP_PARAMETRE, GRP_DB', $_SESSION['GL_USER']['GROUPE']);

$droitTOPMENUS = getDroitTOPMENUS( $_SESSION['GL_USER']['GROUPE']);

$droitMAJ = getDroitMAJ('GRP_GROUPE', $_SESSION['GL_USER']['GROUPE']);

$xid = $_SESSION['DATA_GR']['xid'];
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
	document.formadd.visibleALV.checked=true;

	document.formadd.ajoutCDE.checked=true;
	document.formadd.ajoutLIV.checked=true;
	document.formadd.ajoutALV.checked=true;

	document.formadd.modifCDE.checked=true;
	document.formadd.modifLIV.checked=true;
	document.formadd.modifALV.checked=true;

	document.formadd.supprCDE.checked=true;
	document.formadd.supprLIV.checked=true;
	document.formadd.supprALV.checked=true;

	document.formadd.validCDE.checked=true;
	document.formadd.validLIV.checked=true;
	document.formadd.validALV.checked=true;

	document.formadd.visibleCDE_LIV.checked=true;
	}
	else{
	document.formadd.visibleCDE.checked=false;
	document.formadd.visibleLIV.checked=false;
	document.formadd.visibleALV.checked=false;

	document.formadd.ajoutCDE.checked=false;
	document.formadd.ajoutLIV.checked=false;
	document.formadd.ajoutALV.checked=false;

	document.formadd.modifCDE.checked=false;
	document.formadd.modifLIV.checked=false;
	document.formadd.modifALV.checked=false;

	document.formadd.supprCDE.checked=false;
	document.formadd.supprLIV.checked=false;
	document.formadd.supprALV.checked=false;

	document.formadd.validCDE.checked=false;
	document.formadd.validLIV.checked=false;
	document.formadd.validALV.checked=false;

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
	document.formadd.visibleDOT.checked=true;
	document.formadd.visibleDEC.checked=true;
	document.formadd.visibleTRA.checked=true;
	document.formadd.visibleREC.checked=true;
	document.formadd.visibleREP.checked=true;

	document.formadd.ajoutDOT.checked=true;
	document.formadd.ajoutDEC.checked=true;
	document.formadd.ajoutTRA.checked=true;
	document.formadd.ajoutREC.checked=true;
	document.formadd.ajoutREP.checked=true;

	document.formadd.modifDOT.checked=true;
	document.formadd.modifDEC.checked=true;
	document.formadd.modifTRA.checked=true;
	document.formadd.modifREC.checked=true;
	document.formadd.modifREP.checked=true;

	document.formadd.supprDOT.checked=true;
	document.formadd.supprDEC.checked=true;
	document.formadd.supprTRA.checked=true;
	document.formadd.supprREC.checked=true;
	document.formadd.supprREP.checked=true;

	document.formadd.validDOT.checked=true;
	document.formadd.validDEC.checked=true;
	document.formadd.validTRA.checked=true;
	document.formadd.validREC.checked=true;
	document.formadd.validREP.checked=true;

	document.formadd.visibleMVT.checked=true;
	}
	else{
	document.formadd.visibleDEC.checked=false;
	document.formadd.visibleDOT.checked=false;
	document.formadd.visibleTRA.checked=false;
	document.formadd.visibleREC.checked=false;
	document.formadd.visibleREP.checked=false;

	document.formadd.ajoutDEC.checked=false;
	document.formadd.ajoutDOT.checked=false;
	document.formadd.ajoutTRA.checked=false;
	document.formadd.ajoutREC.checked=false;
	document.formadd.ajoutREP.checked=false;

	document.formadd.modifDEC.checked=false;
	document.formadd.modifDOT.checked=false;
	document.formadd.modifTRA.checked=false;
	document.formadd.modifREC.checked=false;
	document.formadd.modifREP.checked=false;

	document.formadd.supprDEC.checked=false;
	document.formadd.supprDOT.checked=false;
	document.formadd.supprTRA.checked=false;
	document.formadd.supprREC.checked=false;
	document.formadd.supprREP.checked=false;

	document.formadd.validDEC.checked=false;
	document.formadd.validDOT.checked=false;
	document.formadd.validTRA.checked=false;
	document.formadd.validREC.checked=false;
	document.formadd.validREP.checked=false;

	document.formadd.visibleMVT.checked=false;
	}

}

function  doInvent(){
	if(document.formadd.tousINV_STO.checked==true) {
	document.formadd.visibleINV.checked=true;

	document.formadd.ajoutINV.checked=true;

	document.formadd.modifINV.checked=true;

	document.formadd.supprINV.checked=true;

	document.formadd.validINV.checked=true;

	document.formadd.visibleINV_STO.checked=true;
	}
	else{
	document.formadd.visibleINV.checked=false;

	document.formadd.ajoutINV.checked=false;

	document.formadd.modifINV.checked=false;

	document.formadd.supprINV.checked=false;

	document.formadd.validINV.checked=false;

	document.formadd.visibleINV_STO.checked=false;
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
            <td width="180"><?php
			$selectedTab = $_GET['selectedTab'];
			echo parametersMenus($selectedTab , $droit)
			//echo menus($selectedTab, $_SESSION['GL_USER']['EXERCICE']);
			?></td>
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
                <td height="20" bgcolor="#FFCC66" class="leftHeader"><?php echo getlang(450); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><form action="dbgroupe.php?do=update" method="post" name="formadd" id="formadd">
                  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBorder">
                    <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" ><?php echo getlang(440); ?> r un  <?php echo getlang(68); ?></td>
                    </tr>
                    <tr>
                      <td class="text" align="center"><table width="717" border="0" align="left" cellpadding="5" cellspacing="0" class="botBorder">
                        <tbody>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text"<?php echo getlang(68); ?>&nbsp;:&nbsp;</td>
                            <td width="451" align="left" class="text"><input name="nom" type="text" class="formStyle" id="nom" value="<?php echo $_SESSION['DATA_GR']['libelle'];?>" />
                              <span class="mandatory"></span></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text">&nbsp;</td>
                            <td align="left" class="text"><span class="mandatory"></span></td>
                          </tr>
                           <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Commandes/livraisons</td>
                      <td align="left" valign="top" >Visible
                        <input name="visibleCDE_LIV" type="checkbox" id="visibleCDE_LIV" value="1" <?php echo $_SESSION['DATA_GR']['visibleCDE_LIV'];?> />
Tous les droits
<input name="tousCDE_LIV" type="checkbox" id="tousCDE_LIV" value="1" onClick="doCdeLivr();" /></td>
                           </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur COMMANDES&nbsp;:&nbsp;</td>
                            <td class="text"><label>Visible
                              <input name="visibleCDE" type="checkbox" id="visibleCDE" value="1" <?php echo $_SESSION['DATA_GR']['visibleCDE'];?>  />
                            </label>
                            <label>Ajout.
                              <input name="ajoutCDE" type="checkbox" id="ajoutCDE" value="1" <?php echo $_SESSION['DATA_GR']['ajoutCDE'];?>  />
                            </label>
                              <label>Modif.
                                <input name="modifCDE" type="checkbox" id="modifCDE" value="1" <?php echo $_SESSION['DATA_GR']['modifCDE'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprCDE" type="checkbox" id="supprCDE" value="1" <?php echo $_SESSION['DATA_GR']['supprCDE'];?>  />
                              </label>
                              <label>Valid.
                                <input name="validCDE" type="checkbox" id="validCDE" value="1" <?php echo $_SESSION['DATA_GR']['validCDE'];?> />
                              
                              Annul.
                                <input name="annuCDE" type="checkbox" id="annuCDE" value="1" <?php echo $_SESSION['DATA_GR']['annuCDE'];?> />
                              </label></td>
                            </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur LIVRAISONS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleLIV" type="checkbox" id="visibleLIV" value="1" <?php echo $_SESSION['DATA_GR']['visibleLIV'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutLIV" type="checkbox" id="ajoutLIV" value="1" <?php echo $_SESSION['DATA_GR']['ajoutLIV'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifLIV" type="checkbox" id="modifLIV" value="1" <?php echo $_SESSION['DATA_GR']['modifLIV'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprLIV" type="checkbox" id="supprLIV" value="1" <?php echo $_SESSION['DATA_GR']['supprLIV'];?>  />
                                </label>
                                <label>Valid.
  <input name="validLIV" type="checkbox" id="validLIV" value="1" <?php echo $_SESSION['DATA_GR']['validLIV'];?> />
                              
                                Annul.
                                <input name="annuLIV" type="checkbox" id="annuLIV" value="1" <?php echo $_SESSION['DATA_GR']['annuLIV'];?> />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur AUTRES LIVRAISONS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleALV" type="checkbox" id="visibleALV" value="1" <?php echo $_SESSION['DATA_GR']['visibleALV'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutALV" type="checkbox" id="ajoutALV" value="1" <?php echo $_SESSION['DATA_GR']['ajoutALV'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifALV" type="checkbox" id="modifALV" value="1" <?php echo $_SESSION['DATA_GR']['modifALV'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprALV" type="checkbox" id="supprALV" value="1" <?php echo $_SESSION['DATA_GR']['supprALV'];?>  />
                                </label>
                                <label>Valid.
  <input name="validALV" type="checkbox" id="validALV" value="1" <?php echo $_SESSION['DATA_GR']['validALV'];?> />
                              
                                Annul.
                                <input name="annuALV" type="checkbox" id="annuALV" value="1" <?php echo $_SESSION['DATA_GR']['annuALV'];?> />
                                </label></td>
                          </tr>
                          <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Programmation/reversement</td>
                      <td align="left" valign="top" >Visible
                        <input name="visiblePRG_REV" type="checkbox" id="visiblePRG_REV" value="1"  <?php echo $_SESSION['DATA_GR']['visiblePRG_REV'];?> />
Tous les droits
<input name="tousPRG_REV" type="checkbox" id="tousPRG_REV" value="1" onClick="doPrgRev();" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur PROGRAMMATION&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visiblePRG" type="checkbox" id="visiblePRG" value="1" <?php echo $_SESSION['DATA_GR']['visiblePRG'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutPRG" type="checkbox" id="ajoutPRG" value="1" <?php echo $_SESSION['DATA_GR']['ajoutPRG'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifPRG" type="checkbox" id="modifPRG" value="1" <?php echo $_SESSION['DATA_GR']['modifPRG'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprPRG" type="checkbox" id="supprPRG" value="1" <?php echo $_SESSION['DATA_GR']['supprPRG'];?>  />
                               </label>
                               <label>Valid.
  <input name="validPRG" type="checkbox" id="validPRG" value="1" <?php echo $_SESSION['DATA_GR']['validPRG'];?> />
                              
                               Annul.
                               <input name="annuPRG" type="checkbox" id="annuPRG" value="1" <?php echo $_SESSION['DATA_GR']['annuPRG'];?> />
                               </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur REVERSEMENT&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleREV" type="checkbox" id="visibleREV" value="1" <?php echo $_SESSION['DATA_GR']['visibleREV'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutREV" type="checkbox" id="ajoutREV" value="1" <?php echo $_SESSION['DATA_GR']['ajoutREV'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifREV" type="checkbox" id="modifREV" value="1" <?php echo $_SESSION['DATA_GR']['modifREV'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprREV" type="checkbox" id="supprREV" value="1" <?php echo $_SESSION['DATA_GR']['supprREV'];?>  />
                                </label>
                                <label>Valid.
  <input name="validREV" type="checkbox" id="validREV" value="1" <?php echo $_SESSION['DATA_GR']['validREV'];?> />
                              Annul.
                              <input name="annuREV" type="checkbox" id="annuREV" value="1" <?php echo $_SESSION['DATA_GR']['annuREV'];?> />
                                </label></td>
                          </tr>
                           <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Mouvement de stock</td>
                      <td align="left" valign="top" >Visible
                        <input name="visibleMVT" type="checkbox" id="visibleMVT" value="1" <?php echo $_SESSION['DATA_GR']['visibleMVT'];?> />
Tous les droits
<input name="tousMVT" type="checkbox" id="tousMVT" value="1" onClick="doMvt();" /></td>
                           </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur DOTATION&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleDOT" type="checkbox" id="visibleDOT" value="1" <?php echo $_SESSION['DATA_GR']['visibleDOT'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutDOT" type="checkbox" id="ajoutDOT" value="1" <?php echo $_SESSION['DATA_GR']['ajoutDOT'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifDOT" type="checkbox" id="modifDOT" value="1" <?php echo $_SESSION['DATA_GR']['modifDOT'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprDOT" type="checkbox" id="supprDOT" value="1" <?php echo $_SESSION['DATA_GR']['supprDOT'];?>  />
                               </label>
                               <label> Valid.
  <input name="validDOT" type="checkbox" id="validDOT" value="1" <?php echo $_SESSION['DATA_GR']['validDOT'];?> />
                              
                                Annul.
                                <input name="annuDOT" type="checkbox" id="annuDOT" value="1" <?php echo $_SESSION['DATA_GR']['annuDOT'];?> />
                               </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur DECLASSEMENTS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleDEC" type="checkbox" id="visibleDEC" value="1" <?php echo $_SESSION['DATA_GR']['visibleDEC'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutDEC" type="checkbox" id="ajoutDEC" value="1" <?php echo $_SESSION['DATA_GR']['ajoutDEC'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifDEC" type="checkbox" id="modifDEC" value="1" <?php echo $_SESSION['DATA_GR']['modifDEC'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprDEC" type="checkbox" id="supprDEC" value="1" <?php echo $_SESSION['DATA_GR']['supprDEC'];?>  />
                               </label>
                               <label> Valid.
  <input name="validDEC" type="checkbox" id="validDEC" value="1" <?php echo $_SESSION['DATA_GR']['validDEC'];?> />
                              
                                Annul.
                                <input name="annuDEC" type="checkbox" id="annuDEC" value="1" <?php echo $_SESSION['DATA_GR']['annuDEC'];?> />
                               </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur TRANSFERTS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleTRA" type="checkbox" id="visibleTRA" value="1" <?php echo $_SESSION['DATA_GR']['visibleTRA'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutTRA" type="checkbox" id="ajoutTRA" value="1" <?php echo $_SESSION['DATA_GR']['ajoutTRA'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifTRA" type="checkbox" id="modifTRA" value="1" <?php echo $_SESSION['DATA_GR']['modifTRA'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprTRA" type="checkbox" id="supprTRA" value="1" <?php echo $_SESSION['DATA_GR']['supprTRA'];?>  />
                              </label>
                              <label>  Valid.
  <input name="validTRA" type="checkbox" id="validTRA" value="1" <?php echo $_SESSION['DATA_GR']['validTRA'];?> />
                              Annul.
                              <input name="annuTRA" type="checkbox" id="annuTRA" value="1" <?php echo $_SESSION['DATA_GR']['annuTRA'];?> />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur RECONDITIONNEMENTS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleREC" type="checkbox" id="visibleREC" value="1" <?php echo $_SESSION['DATA_GR']['visibleREC'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutREC" type="checkbox" id="ajoutREC" value="1" <?php echo $_SESSION['DATA_GR']['ajoutREC'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifREC" type="checkbox" id="modifREC" value="1" <?php echo $_SESSION['DATA_GR']['modifREC'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprREC" type="checkbox" id="supprREC" value="1" <?php echo $_SESSION['DATA_GR']['supprREC'];?>  />
                              </label>
                              <label>  Valid.
                                <input name="validREC" type="checkbox" id="validREC" value="1" <?php echo $_SESSION['DATA_GR']['validREC'];?>  />
                                
                                Annul.
                                <input name="annuREC" type="checkbox" id="annuREC" value="1" <?php echo $_SESSION['DATA_GR']['annuREC'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur REPORTS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleREP" type="checkbox" id="visibleREP" value="1"<?php echo $_SESSION['DATA_GR']['visibleREP'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutREP" type="checkbox" id="ajoutREP" value="1" <?php echo $_SESSION['DATA_GR']['ajoutREP'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifREP" type="checkbox" id="modifREP" value="1" <?php echo $_SESSION['DATA_GR']['modifREP'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprREP" type="checkbox" id="supprREP" value="1" <?php echo $_SESSION['DATA_GR']['supprREP'];?>  />
                              </label>
                              <label>  Valid.
  <input name="validREP" type="checkbox" id="validREP" value="1" <?php echo $_SESSION['DATA_GR']['validREP'];?> />
                              Annul.
                              <input name="annuREP" type="checkbox" id="annuREP" value="1" <?php echo $_SESSION['DATA_GR']['annuREP'];?> />
                              </label></td>
                          </tr>
                           <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Etats &amp; imprimables</td>
                      <td align="left" valign="top" >Visible
                        <input name="visibleETA" type="checkbox" id="visibleETA" value="1" <?php echo $_SESSION['DATA_GR']['visibleETA'];?> /></td>
                           </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">&nbsp;</td>
                            <td align="left" class="text">&nbsp;</td>
                          </tr>
                          <tr class="header2Bg">
                      <td align="left" valign="top" class="boldText" >&nbsp;Inventaire</td>
                      <td align="left" valign="top" >Visible
                        <input name="visibleINV_STO" type="checkbox" id="visibleINV_STO" value="1" <?php echo $_SESSION['DATA_GR']['visibleINV_STO'];?> />
Tous les droits
<input name="tousINV_STO" type="checkbox" id="tousINV_STO" value="1" onClick="doInvent();" /></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur INVENTAIRES&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleINV" type="checkbox" id="visibleINV" value="1" <?php echo $_SESSION['DATA_GR']['visibleINV'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutINV" type="checkbox" id="ajoutINV" value="1" <?php echo $_SESSION['DATA_GR']['ajoutINV'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifINV" type="checkbox" id="modifINV" value="1" <?php echo $_SESSION['DATA_GR']['modifINV'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprINV" type="checkbox" id="supprINV" value="1" <?php echo $_SESSION['DATA_GR']['supprINV'];?>  />
                              </label>
                              <label>  Valid.
  <input name="validINV" type="checkbox" id="validINV" value="1" <?php echo $_SESSION['DATA_GR']['validINV'];?> />
                              
                                Annul.
                                <input name="annuINV" type="checkbox" id="annuINV" value="1" <?php echo $_SESSION['DATA_GR']['annuINV'];?> />
                              </label></td>
                          </tr>
                           <tr class="header2Bg">
                             <td align="left" valign="top" class="boldText" >&nbsp;Param&eacute;trage</td>
                             <td align="left" valign="top" >Visible
                               <input name="visiblePAR" type="checkbox" id="visiblePAR" value="1" <?php echo $_SESSION['DATA_GR']['visiblePAR'];?> />
                               Tous les droits
                               <input name="tousPAR" type="checkbox" id="tousPAR" value="1" onClick="doPar();" /></td>
                           </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur PERSONNEL&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visiblePER" type="checkbox" id="visiblePER" value="1" <?php echo $_SESSION['DATA_GR']['visiblePER'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutPER" type="checkbox" id="ajoutPER" value="1" <?php echo $_SESSION['DATA_GR']['ajoutPER'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifPER" type="checkbox" id="modifPER" value="1" <?php echo $_SESSION['DATA_GR']['modifPER'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprPER" type="checkbox" id="supprPER" value="1" <?php echo $_SESSION['DATA_GR']['supprPER'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur UTILISATEURS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleUTI" type="checkbox" id="visibleUTI" value="1" <?php echo $_SESSION['DATA_GR']['visibleUTI'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutUTI" type="checkbox" id="ajoutUTI" value="1" <?php echo $_SESSION['DATA_GR']['ajoutUTI'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifUTI" type="checkbox" id="modifUTI" value="1" <?php echo $_SESSION['DATA_GR']['modifUTI'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprUTI" type="checkbox" id="supprUTI" value="1" <?php echo $_SESSION['DATA_GR']['supprUTI'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur GROUPE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleGRP" type="checkbox" id="visibleGRP" value="1" <?php echo $_SESSION['DATA_GR']['visibleGRP'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutGRP" type="checkbox" id="ajoutGRP" value="1" <?php echo $_SESSION['DATA_GR']['ajoutGRP'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifGRP" type="checkbox" id="modifGRP" value="1" <?php echo $_SESSION['DATA_GR']['modifGRP'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprGRP" type="checkbox" id="supprGRP" value="1" <?php echo $_SESSION['DATA_GR']['supprGRP'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur LOGS&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleLOG" type="checkbox" id="visibleLOG" value="1" <?php echo $_SESSION['DATA_GR']['visibleLOG'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutLOG" type="checkbox" id="ajoutLOG" value="1" <?php echo $_SESSION['DATA_GR']['ajoutLOG'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifLOG" type="checkbox" id="modifLOG" value="1" <?php echo $_SESSION['DATA_GR']['modifLOG'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprLOG" type="checkbox" id="supprLOG" value="1" <?php echo $_SESSION['DATA_GR']['supprLOG'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text">Droits sur CATEGORIE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleCAT" type="checkbox" id="visibleCAT" value="1" <?php echo $_SESSION['DATA_GR']['visibleCAT'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutCAT" type="checkbox" id="ajoutCAT" value="1" <?php echo $_SESSION['DATA_GR']['ajoutCAT'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifCAT" type="checkbox" id="modifCAT" value="1" <?php echo $_SESSION['DATA_GR']['modifCAT'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprCAT" type="checkbox" id="supprCAT" value="1" <?php echo $_SESSION['DATA_GR']['supprCAT'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text">Droits sur PRODUIT&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visiblePRD" type="checkbox" id="visiblePRD" value="1" <?php echo $_SESSION['DATA_GR']['visiblePRD'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutPRD" type="checkbox" id="ajoutPRD" value="1" <?php echo $_SESSION['DATA_GR']['ajoutPRD'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifPRD" type="checkbox" id="modifPRD" value="1" <?php echo $_SESSION['DATA_GR']['modifPRD'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprPRD" type="checkbox" id="supprPRD" value="1" <?php echo $_SESSION['DATA_GR']['supprPRD'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text">Droits sur CONDITIONNEMENT&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleCND" type="checkbox" id="visibleCND" value="1" <?php echo $_SESSION['DATA_GR']['visibleCND'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutCND" type="checkbox" id="ajoutCND" value="1" <?php echo $_SESSION['DATA_GR']['ajoutCND'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifCND" type="checkbox" id="modifCND" value="1" <?php echo $_SESSION['DATA_GR']['modifCND'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprCND" type="checkbox" id="supprCND" value="1" <?php echo $_SESSION['DATA_GR']['supprCND'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td width="246" align="right" valign="middle" class="text">Droits sur UNITE DE MESURE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleUNI" type="checkbox" id="visibleUNI" value="1" <?php echo $_SESSION['DATA_GR']['visibleUNI'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutUNI" type="checkbox" id="ajoutUNI" value="1" <?php echo $_SESSION['DATA_GR']['ajoutUNI'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifUNI" type="checkbox" id="modifUNI" value="1" <?php echo $_SESSION['DATA_GR']['modifUNI'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprUNI" type="checkbox" id="supprUNI" value="1" <?php echo $_SESSION['DATA_GR']['supprUNI'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur BAREME&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleBAR" type="checkbox" id="visibleBAR" value="1" <?php echo $_SESSION['DATA_GR']['visibleBAR'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutBAR" type="checkbox" id="ajoutBAR" value="1" <?php echo $_SESSION['DATA_GR']['ajoutBAR'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifBAR" type="checkbox" id="modifBAR" value="1" <?php echo $_SESSION['DATA_GR']['modifBAR'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprBAR" type="checkbox" id="supprBAR" value="1" <?php echo $_SESSION['DATA_GR']['supprBAR'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur TYPE LOCALITE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleTLO" type="checkbox" id="visibleTLO" value="1" <?php echo $_SESSION['DATA_GR']['visibleTLO'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutTLO" type="checkbox" id="ajoutTLO" value="1" <?php echo $_SESSION['DATA_GR']['ajoutTLO'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifTLO" type="checkbox" id="modifTLO" value="1" <?php echo $_SESSION['DATA_GR']['modifTLO'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprTLO" type="checkbox" id="supprTLO" value="1" <?php echo $_SESSION['DATA_GR']['supprTLO'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur LOCALITE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleLOC" type="checkbox" id="visibleLOC" value="1" <?php echo $_SESSION['DATA_GR']['visibleLOC'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutLOC" type="checkbox" id="ajoutLOC" value="1" <?php echo $_SESSION['DATA_GR']['ajoutLOC'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifLOC" type="checkbox" id="modifLOC" value="1" <?php echo $_SESSION['DATA_GR']['modifLOC'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprLOC" type="checkbox" id="supprLOC" value="1" <?php echo $_SESSION['DATA_GR']['supprLOC'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur TYPE SERVICE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleTSE" type="checkbox" id="visibleTSE" value="1" <?php echo $_SESSION['DATA_GR']['visibleTSE'];?>  />
                            </label>
                              <label>Ajout.
                                <input name="ajoutTSE" type="checkbox" id="ajoutTSE" value="1" <?php echo $_SESSION['DATA_GR']['ajoutTSE'];?>  />
                              </label>
                              <label>Modif.
                                <input name="modifTSE" type="checkbox" id="modifTSE" value="1" <?php echo $_SESSION['DATA_GR']['modifTSE'];?>  />
                              </label>
                              <label>Suppr.
                                <input name="supprTSE" type="checkbox" id="supprTSE" value="1" <?php echo $_SESSION['DATA_GR']['supprTSE'];?>  />
                              </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur SERVICE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleSER" type="checkbox" id="visibleSER" value="1" <?php echo $_SESSION['DATA_GR']['visibleSER'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutSER" type="checkbox" id="ajoutSER" value="1" <?php echo $_SESSION['DATA_GR']['ajoutSER'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifSER" type="checkbox" id="modifSER" value="1" <?php echo $_SESSION['DATA_GR']['modifSER'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprSER" type="checkbox" id="supprSER" value="1" <?php echo $_SESSION['DATA_GR']['supprSER'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur <?php echo getlang(213); ?></td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleMAG" type="checkbox" id="visibleMAG" value="1" <?php echo $_SESSION['DATA_GR']['visibleMAG'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutMAG" type="checkbox" id="ajoutMAG" value="1" <?php echo $_SESSION['DATA_GR']['ajoutMAG'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifMAG" type="checkbox" id="modifMAG" value="1" <?php echo $_SESSION['DATA_GR']['modifMAG'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprMAG" type="checkbox" id="supprMAG" value="1" <?php echo $_SESSION['DATA_GR']['supprMAG'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur <?php echo getlang(66); ?></td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleFOU" type="checkbox" id="visibleFOU" value="1" <?php echo $_SESSION['DATA_GR']['visibleFOU'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutFOU" type="checkbox" id="ajoutFOU" value="1" <?php echo $_SESSION['DATA_GR']['ajoutFOU'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifFOU" type="checkbox" id="modifFOU" value="1" <?php echo $_SESSION['DATA_GR']['modifFOU'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprFOU" type="checkbox" id="supprFOU" value="1" <?php echo $_SESSION['DATA_GR']['supprFOU'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur TYPE BENEFICIAIRE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleTBE" type="checkbox" id="visibleTBE" value="1" <?php echo $_SESSION['DATA_GR']['visibleTBE'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutTBE" type="checkbox" id="ajoutTBE" value="1" <?php echo $_SESSION['DATA_GR']['ajoutTBE'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifTBE" type="checkbox" id="modifTBE" value="1" <?php echo $_SESSION['DATA_GR']['modifTBE'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprTBE" type="checkbox" id="supprTBE" value="1" <?php echo $_SESSION['DATA_GR']['supprTBE'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur BENEFICIAIRE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleBEN" type="checkbox" id="visibleBEN" value="1" <?php echo $_SESSION['DATA_GR']['visibleBEN'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutBEN" type="checkbox" id="ajoutBEN" value="1" <?php echo $_SESSION['DATA_GR']['ajoutBEN'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifBEN" type="checkbox" id="modifBEN" value="1" <?php echo $_SESSION['DATA_GR']['modifBEN'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprBEN" type="checkbox" id="supprBEN" value="1" <?php echo $_SESSION['DATA_GR']['supprBEN'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur EXERCICE BUDGETAIRE&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleEXE" type="checkbox" id="visibleEXE" value="1" <?php echo $_SESSION['DATA_GR']['visibleEXE'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutEXE" type="checkbox" id="ajoutEXE" value="1" <?php echo $_SESSION['DATA_GR']['ajoutEXE'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifEXE" type="checkbox" id="modifEXE" value="1" <?php echo $_SESSION['DATA_GR']['modifEXE'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprEXE" type="checkbox" id="supprEXE" value="1" <?php echo $_SESSION['DATA_GR']['supprEXE'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur TYPE DOTATION&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visibleTDO" type="checkbox" id="visibleTDO" value="1" <?php echo $_SESSION['DATA_GR']['visibleTDO'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutTDO" type="checkbox" id="ajoutTDO" value="1" <?php echo $_SESSION['DATA_GR']['ajoutTDO'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifTDO" type="checkbox" id="modifTDO" value="1" <?php echo $_SESSION['DATA_GR']['modifTDO'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprTDO" type="checkbox" id="supprTDO" value="1" <?php echo $_SESSION['DATA_GR']['supprTDO'];?>  />
                                </label></td>
                          </tr>
                          <tr align="left" valign="top">
                            <td align="right" valign="middle" class="text">Droits sur PARAMETRES GENERAUX&nbsp;:&nbsp;</td>
                            <td align="left" class="text"><label>Visible
                              <input name="visiblePGL" type="checkbox" id="visiblePGL" value="1" <?php echo $_SESSION['DATA_GR']['visiblePGL'];?>  />
                              </label>
                              <label>Ajout.
                                <input name="ajoutPGL" type="checkbox" id="ajoutPGL" value="1" <?php echo $_SESSION['DATA_GR']['ajoutPGL'];?>  />
                                </label>
                              <label>Modif.
                                <input name="modifPGL" type="checkbox" id="modifPGL" value="1" <?php echo $_SESSION['DATA_GR']['modifPGL'];?>  />
                                </label>
                              <label>Suppr.
                                <input name="supprPGL" type="checkbox" id="supprPGL" value="1" <?php echo $_SESSION['DATA_GR']['supprPGL'];?>  />
                                </label></td>
                          </tr>
                          <tr>
                            <td colspan="2"><input name="exercicecourant" type="hidden" id="exercicecourant" value="<?php echo $_SESSION['GL_USER']['EXERCICE']; ?>" />
                              <input name="statusexercice" type="hidden" id="statusexercice" value="<?php echo $_SESSION['GL_USER']['STATUT_EXERCICE']; ?>" />
                              <input name="myaction" type="hidden" id="myaction" value="" />
                              <input name="xid" type='hidden' id="xid" value="<?php echo $xid; ?>" /></td>
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
