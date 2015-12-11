<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('funclivraison.php');
$libel=getlang(86);
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

(isset($_SESSION['DATA_LVR']['exercice']) 		? $exercice 	= $_SESSION['DATA_LVR']['exercice']: $exercice ='');
(isset($_SESSION['DATA_LVR']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_LVR']['nbreLigne']: $nbreLigne ='');
(isset($_SESSION['DATA_LVR']['libellecde']) 		? $libellecde 	= $_SESSION['DATA_LVR']['libellecde']: $libellecde ='');
(isset($_SESSION['DATA_LVR']['reflivraison']) 		? $codelivraison 	= $_SESSION['DATA_LVR']['reflivraison']: $codelivraison ='');
(isset($_SESSION['DATA_LVR']['datelivraison']) 		? $datelivraison 	= $_SESSION['DATA_LVR']['datelivraison']: $datelivraison ='');
(isset($_SESSION['DATA_LVR']['libellelivraison']) 		? $libellelivraison 	= $_SESSION['DATA_LVR']['libellelivraison']: $libellelivraison ='');
$libellelivraison= $libel.' n° '.$codelivraison. ' - '. $datelivraison ;
(isset($_SESSION['DATA_LVR']['ligne']) ? $data= $_SESSION['DATA_LVR']['ligne'] : $data=array());
//Ligne
$ligne = ligneEtatLivraison($nbreLigne,$data);

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
    <td>
    <!--  Libellé -->
    <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                <tr>
                  <td height="20" class="leftHeader" colspan="3"><h2><?php echo (stripslashes($libellelivraison)); ?></h2></td>
                </tr>
	<tr class="header2">
                      <td width="20%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;<?php echo getlang(62); ?> : <strong><?php echo $exercice; ?>&nbsp;&nbsp;</strong></td>
                      <td width="35%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;N&deg;: <strong><?php echo $codelivraison; ?>&nbsp;&nbsp;</strong></td>
          <td width="45%" height="25" align="left" valign="middle" nowrap class="header2Bg">&nbsp;Date : <strong><?php echo $datelivraison; ?></strong></td>
           </tr>
      </table>
    <p>&nbsp;</p>
    <tr>
    <td>
    <table width="723" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td width="10" align=center valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong>N°</strong></div></td>
                                  <td width="15" align=center valign="middle" class="botBorderTdall header2Bg" nowrap="nowrap"><div align="left"><strong>Réf. Lot</strong></div></td>
                                  <td width="180" align=right valign="middle" class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(199); ?></strong> </div></td>

                                 
                                  <td width="60" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center" nowrap><strong><?php echo getlang(367); ?></strong></div></td>
                                  <td width="50" align=center valign="middle" class="botBorderTdall header2Bg" nowrap><div align="center"><strong><?php echo getlang(368); ?></strong></div></td>
								  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(204); ?></strong></div></td>
								  <td width="40" align=center valign="middle" class="botBorderTdall header2Bg" nowrap><div align="center"><strong><?php echo getlang(205); ?></strong></div></td>
                                  <td width="40" align=center valign="middle" class="botBorderTdall header2Bg" nowrap><div align="center"><strong>Mnt total</strong></div></td>
                                  <td width="40" align=center valign="middle" nowrap class="header2Bg botBorderTdall"><strong><?php echo getlang(201); ?> </strong></td>
                                </tr>
                                <?php echo $ligne; ?>
        </tbody>
      </table>
      </td>
  </tr>
  <tr><td>
    <p></p>  
    
    <!--  End Libellé -->
    <table width="100%" cellspacing="5" cellpadding="10" >
      <tr valign="top" class="EtatText">
        <td width="30%" align="left"><div><strong><u><?php echo getlang(66); ?></u></strong></div></td>
        <td width="30%" align="left"><div></div></td>
        <td width="30%" align="left"><div><strong><u><?php echo getlang(451); ?></u></strong></div></td>
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
