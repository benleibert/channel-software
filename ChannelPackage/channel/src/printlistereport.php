<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('funcreport.php');

$INFO = getInfoGenerale($_SESSION['GL_USER']['MAGASIN']);
$nommag = getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin');
(isset($INFO['ID']) ? $id = $INFO['ID']: $id ='');
(isset($INFO['INF_CLIENT']) ? $client = $INFO['INF_CLIENT']: $client ='');
(isset($INFO['INF_DATEACQ']) ? $dateacq = $INFO['INF_DATEACQ']: $dateacq  ='');
(isset($INFO['INF_LICENCE']) ? $licence = $INFO['INF_LICENCE']: $licence ='');
(isset($INFO['INF_MINISTERE']) && $INFO['INF_MINISTERE']!='' ? $ministere = $INFO['INF_MINISTERE']: $ministere ='');
(isset($INFO['INF_SECRETARIAT']) && $INFO['INF_SECRETARIAT']!='' ? $secretariat = '<div align="center">-----------------</div>'.$INFO['INF_SECRETARIAT']: $secretariat ='');
(isset($INFO['INF_DIRECTION']) && $INFO['INF_DIRECTION']!='' ? $direction = '<div align="center">-----------------</div>'.$INFO['INF_DIRECTION']: $direction ='');
(isset($INFO['INF_SERVICE']) && $INFO['INF_SERVICE']!='' ? $service = '<div align="center">-----------------</div>'.$INFO['INF_SERVICE']: $service ='');
(isset($INFO['INF_CSPS']) && $INFO['INF_CSPS']!='' ? $csps = '<div align="center">-----------------</div>'.$INFO['INF_CSPS']: $csps ='');
(isset($INFO['INF_PAYS']) ? $pays = $INFO['INF_PAYS']: $pays ='');
(isset($INFO['INF_VILLE']) ? $ville = $INFO['INF_VILLE']: $ville ='');
(isset($INFO['INF_DEVISE']) ? $devise = $INFO['INF_DEVISE']: $devise ='');
($dateacq =='0000-00-00'  ? $dateacq='' : $dateacq =frFormat2($dateacq));
(isset($INFO['INF_SIGNATEUR1']) ? $signateur1 = $INFO['INF_SIGNATEUR1']: $signateur1 ='');
(isset($INFO['INF_NOMSIGNATEUR1']) ? $nomsignateur1 = $INFO['INF_NOMSIGNATEUR1']: $nomsignateur1 ='');
(isset($INFO['INF_SIGNATEUR2']) ? $signateur2 = $INFO['INF_SIGNATEUR2']: $signateur2 ='');
(isset($INFO['INF_NOMSIGNATEUR2']) ? $nomsignateur2 = $INFO['INF_NOMSIGNATEUR2']: $nomsignateur2 ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $signateur3 = $INFO['INF_SIGNATEUR3']: $signateur3 ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $nomsignateur3 = $INFO['INF_NOMSIGNATEUR3']: $nomsignateur3 ='');
(isset($INFO['INF_SIGNATEUR4']) ? $signateur4 = $INFO['INF_SIGNATEUR4']: $signateur4 ='');
(isset($INFO['INF_NOMSIGNATEUR4']) ? $nomsignateur4 = $INFO['INF_NOMSIGNATEUR4']: $nonsignateur4 ='');
(isset($INFO['LOGO']) && $INFO['LOGO']!='' ? $logo = '<img src="../upload/'.$INFO['LOGO'].'" />': $logo ='');
$libellecde = 'Liste des reports';

//Ligne
if(isset($_SESSION['WHERE']) && $_SESSION['WHERE']!='') {
	$ligne = ligneEtatListeReport($_SESSION['WHERE'],'','');
}
else {
$ligne = ligneEtatListeReport("report.ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."  AND report.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'",'','');
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
<style type="text/css">
<!--
.Style2 {
	font-size: x-large;
	font-family: "Times New Roman", Times, serif;
}
-->
</style>
</head><body>
<table width="700" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" cellspacing="0" cellpadding="0">
      <tr valign="top" class="EtatText">
        <td width="35%" rowspan="4"><div align="left"><strong><?php echo (mb_strtoupper(stripslashes($ministere))); ?></strong></div>                    <strong><?php echo (mb_strtoupper(stripslashes($secretariat))); ?></strong><strong><?php echo (mb_strtoupper(stripslashes($direction))); ?></strong><strong><?php echo (mb_strtoupper(stripslashes($service))); ?></strong><strong><?php echo (mb_strtoupper(stripslashes($csps))); ?></strong></td>
        <td width="30%" rowspan="4" align="center">&nbsp;</td>
        <td width="35%"><div align="center"><strong><?php echo (mb_strtoupper(stripslashes($pays))); ?><br>
          <?php echo stripslashes($devise); ?></strong></div></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td>&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td align="center">
          <?php echo mb_strtoupper(stripslashes($nommag)); ?>, le <?php echo date("d/m/Y"); ?></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>
    <!--  Libellé -->
    <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                <tr>
                  <td width="100%" height="20" class="leftHeader"><h2><?php echo getlang(360); ?></h2></td>
                </tr>
      </table>
    <p>&nbsp;</p>
    <tr>
    <td>
    <table width="723" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td width="5%" align=center valign="middle" class="botBorderTdall header2Bg"><strong>N°</strong></td>
                                  <td width="5%" height="25" align="center" valign="middle" class="botBorderTdall header2Bg" nowrap><strong>...</strong></td>
                    <td width="10%" height="25" align="center" valign="middle" class="botBorderTdall header2Bg"><strong><?php echo getlang(33); ?></strong></td>
                    <td width="10%" align="left" valign="middle" class="botBorderTdall header2Bg"><strong><?php echo getlang(446); ?></strong></td>
                    <td width="30%" height="25" align="left" valign="middle" class="botBorderTdall header2Bg"><strong>&nbsp;&nbsp;<span class="header2Bg"><?php echo getlang(338); ?> </span></strong></td>
                    
							    </tr>
                                <?php echo $ligne; ?>
        </tbody>
      </table>
      </td>
  </tr>
  <tr><td>
    <p>&nbsp;</p>  
    <!--  End Libellé --></td>
  </tr>
 
  
</table>
</body>
</html>
