<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('funcreversement.php');

//print_r($_SESSION['DATA_ETAT']);
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

(isset($INFO['INF_SIGNATEUR2']) ? $chef = $INFO['INF_SIGNATEUR2']: $chef ='');
(isset($INFO['INF_NOMSIGNATEUR2']) ? $nomchef = $INFO['INF_NOMSIGNATEUR2']: $nomchef ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $directeur = $INFO['INF_SIGNATEUR3']: $directeur ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $nomdirecteur = $INFO['INF_NOMSIGNATEUR3']: $nomdirecteur ='');

(isset($INFO['INF_SIGNATEUR3']) ? $gestionnaire = $INFO['INF_SIGNATEUR3']: $gestionnaire ='');
(isset($INFO['INF_SIGNATEUR4']) ? $daf = $INFO['INF_SIGNATEUR4']: $daf ='');


$libelle = 'Reversements '.getField('CODE_MAGASIN',$_SESSION['GL_USER']['MAGASIN'],'SER_NOM','magasin');
(isset($_SESSION['WHERE']) && $_SESSION['WHERE']!='' ? $where =' AND '.$_SESSION['WHERE']: $where ='');

//Ligne
if(isset($_SESSION['WHERE']) && $_SESSION['WHERE']!='') {
	$affichage = ligneEtatVersement(' AND '.$_SESSION['WHERE'],'','');
}
else {
	$affichage  = ligneEtatVersement(" AND programmation.ID_EXERCICE=".$_SESSION['GL_USER']['EXERCICE']."  AND CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'", $dot='');
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
<table width="800" align="center" cellpadding="0" cellspacing="0">
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
    <td>
    <!--  Libellé -->
    <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                <tr>
                  <td width="20" height="20"  colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20" class="leftHeader" colspan="2"><h2><?php echo (stripslashes($libelle)); ?></h2></td>
                </tr>
	</table>
    <p>&nbsp;</p>
    <tr>
    <td>
    <table width="1084" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td width="15" align=center valign="middle" class="botBorderTdall header2Bg"><strong>N&deg;</strong></td>
                                  <td width="15" align=center valign="middle" class="botBorderTdall header2Bg"><strong><?php echo getlang(54); ?></strong></td>
                                  <td width="300" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(16); ?></strong></div></td>
                                  <td width="20" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong>Dotation</strong></div></td>
                                  <td width="100" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(42); ?> versement</strong></div></td>
                                  <td width="100" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(224); ?> d&ucirc;</strong></div></td>
                                  <td width="120" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong>Mnt versement</strong></div></td>
                                  <td width="180" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong>R&eacute;f. du ch&egrave;que / Quitance</strong></div></td>
                                  <td width="180" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left">
                                    <p><strong>Observations</strong>                                  </p>
                                  </div></td>
                                </tr>
                                <?php echo $affichage; ?>
        </tbody>
      </table>
      </td>
  </tr>
  <tr><td>
    <p>&nbsp;</p>
    <table width="100%" cellspacing="5" cellpadding="10" >
      <tr valign="top" class="EtatText">
        <td width="30%" align="left"><div><strong><u>Chef section recouvrement</u></strong></div></td>
        <td width="30%" align="left"><div><strong><u><?php echo $chef; ?></u></strong></div></td>
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
    </table>    <!--  End Libellé --></td>
  </tr>
 
  
</table>
</body>
</html>
