<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('functransfert.php');

$INFO = getInfoGenerale($_SESSION['GL_USER']['MAGASIN']);
$nommag = getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin');
(isset($INFO['ID']) ? $id = $INFO['ID']: $id ='');
(isset($INFO['INF_CLIENT']) ? $client = $INFO['INF_CLIENT']: $client ='');
(isset($INFO['INF_DATEACQ']) ? $dateacq = $INFO['INF_DATEACQ']: $dateacq  ='');
(isset($INFO['INF_LICENCE']) ? $licence = $INFO['INF_LICENCE']: $licence ='');
(isset($INFO['INF_MINISTERE']) ? $ministere = $INFO['INF_MINISTERE']: $ministere ='');
(isset($INFO['INF_SECRETARIAT']) ? $secretariat = $INFO['INF_SECRETARIAT']: $secretariat ='');
(isset($INFO['INF_DIRECTION']) ? $direction = $INFO['INF_DIRECTION']: $direction ='');
(isset($INFO['INF_SERVICE']) ? $service = $INFO['INF_SERVICE']: $service ='');
(isset($INFO['INF_PAYS']) ? $pays = $INFO['INF_PAYS']: $pays ='');
(isset($INFO['INF_VILLE']) ? $ville = $INFO['INF_VILLE']: $ville ='');
(isset($INFO['INF_DEVISE']) ? $devise = $INFO['INF_DEVISE']: $devise ='');
($dateacq =='0000-00-00'  ? $dateacq='' : $dateacq =frFormat2($dateacq));

(isset($INFO['INF_SIGNATEUR2']) ? $chef = $INFO['INF_SIGNATEUR2']: $chef ='');
(isset($INFO['INF_NOMSIGNATEUR2']) ? $nomchef = $INFO['INF_NOMSIGNATEUR2']: $nomchef ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $directeur = $INFO['INF_SIGNATEUR3']: $directeur ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $nomdirecteur = $INFO['INF_NOMSIGNATEUR3']: $nomdirecteur ='');

(isset($INFO['INF_SIGNATEUR3']) ? $gestionnaire = $INFO['INF_SIGNATEUR3']: $gestionnaire ='');
(isset($INFO['INF_SIGNATEUR4']) ? $daf = $INFO['INF_SIGNATEUR4']: $daf ='');

$libellecde = 'Liste des transferts';
//Ligne
if(isset($_SESSION['WHERE']) && $_SESSION['WHERE']!='') {
	$ligne = ligneEtatListeTransfert($_SESSION['WHERE'],'','');
}
else {
$ligne = ligneEtatListeTransfert("transfert.ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."  AND transfert.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'",'','');
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


</head>
<body>
<table width="700" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" cellspacing="0" cellpadding="0">
      <tr valign="top" class="EtatText">
        <td width="35%"><div align="left"><strong><?php echo (mb_strtoupper(stripslashes($ministere))); ?></strong></div></td>
        <td width="30%">&nbsp;</td>
        <td width="35%"><div align="center"><strong><?php echo (mb_strtoupper(stripslashes($pays))); ?><br>
          <?php echo stripslashes($devise); ?></strong></div></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (mb_strtoupper(stripslashes($secretariat))); ?></strong></td>
        <td>&nbsp;</td>
        <td rowspan="4" align="center"><?php echo mb_strtoupper(stripslashes($nommag)); ?>, le <?php echo date("d/m/Y"); ?></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        <td>&nbsp;</td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (mb_strtoupper(stripslashes($direction))); ?></strong></td>
        <td>&nbsp;</td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        <td>&nbsp;</td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (strtoupper(stripslashes($service))); ?></strong></td>
        <td>&nbsp;</td>
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
                  <td width="100%" height="20" class="leftHeader"><h3><?php echo getlang(358); ?></h3></td>
                </tr>
      </table>
    <p>&nbsp;</p>
    <tr>
    <td>
    <table width="723" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td width="10" align=center valign="middle" class="botBorderTdall header2Bg"><strong>N°</strong></td>
                                  <td width="100" align=right valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(34); ?></strong></div></td>
                                  <td width="80" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(42); ?></strong></div></td>
                                  <td width="100" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(98); ?></strong></div></td>
                                  <td width="100" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(91); ?></strong></div></td>
                                  <td width="100" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(90); ?></strong></div></td>
                                </tr>
                                <?php echo $ligne; ?>
        </tbody>
      </table>
      </td>
  </tr>
  <tr><td>
    <p>&nbsp;</p>  
    <!--  End Libellé -->
    <table width="100%" cellspacing="5" cellpadding="10" >
      <tr valign="top" class="EtatText">
        <td width="30%" align="left"><div><strong><u><?php echo $chef; ?></u></strong></div></td>
        <td width="30%" align="left"><div></div></td>
        <td width="30%" align="left"><div><strong><u><?php echo $daf; ?></u></strong></div></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
        <td width="30%" align="left">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
 
  
</table>
</body>
</html>
