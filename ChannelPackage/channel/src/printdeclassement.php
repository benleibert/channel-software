<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('funcdeclassement.php');
$libel= getlang(111);

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

(isset($_SESSION['DATA_DEC']['exercice']) 		? $exercice 	= $_SESSION['DATA_DEC']['exercice']: $exercice ='');
(isset($_SESSION['DATA_DEC']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_DEC']['nbreLigne']: $nbreLigne ='');
(isset($_SESSION['DATA_DEC']['raison']) 		? $raison 	= ' '.$_SESSION['DATA_DEC']['raison']: $raison ='');
(isset($_SESSION['DATA_DEC']['refdeclassement']) 		? $code 	= $_SESSION['DATA_DEC']['refdeclassement']: $code ='');
(isset($_SESSION['DATA_DEC']['datedeclassement']) 		? $date 	= $_SESSION['DATA_DEC']['datedeclassement']: $date ='');
(isset($_SESSION['DATA_DEC']['cabinet']) 		? $cabinet 	= $_SESSION['DATA_DEC']['cabinet']: $cabinet ='');
(isset($_SESSION['DATA_DEC']['refrapport']) 		? $refrapport 	= $_SESSION['DATA_DEC']['refrapport']: $refrapport ='');
(isset($_SESSION['DATA_DEC']['ligne']) ? $data= $_SESSION['DATA_DEC']['ligne'] : $data=array());
//Ligne
$libelle =$libel.' n°'. $code ; 	
$ligne = ligneEtatDeclassement($nbreLigne,$data);

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
        <td width="30%" rowspan="7" align="center">&nbsp;</td>
        <td width="35%"><div align="center"><strong><?php echo (mb_strtoupper(stripslashes($pays))); ?><br>
          <?php echo stripslashes($devise); ?></strong></div></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (mb_strtoupper(stripslashes($secretariat))); ?></strong></td>
        <td rowspan="4" align="center"><?php echo mb_strtoupper(stripslashes($nommag)); ?>, le <?php echo date("d/m/Y"); ?></td>
      </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (mb_strtoupper(stripslashes($direction))); ?></strong></td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><div align="center">-----------------</div></td>
        </tr>
      <tr valign="top" class="EtatText">
        <td><strong><?php echo (mb_strtoupper(stripslashes($service))); ?></strong></td>
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
                  <td height="20" class="leftHeader" colspan="3"><h2><?php echo (stripslashes($libelle)); ?></h2></td>
                </tr>
	<tr class="header2">
                      <td width="20%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;<?php echo getlang(62); ?> : <strong><?php echo $exercice; ?>&nbsp;&nbsp;</strong></td>
                      <td width="30%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;Réf.: <strong><?php echo $code; ?>&nbsp;&nbsp;</strong></td>
          <td width="50%" height="25" align="left" valign="middle" nowrap class="header2Bg">&nbsp;Date : <strong><?php echo $date; ?></strong></td>
           </tr>
           <tr class="header2">
             <td height="25" colspan="3" align="left" valign="middle" nowrap class="header2Bg"><?php echo getlang(99); ?> : <strong><?php echo $raison; ?>&nbsp;&nbsp;</strong></td>
           </tr>
           <tr class="header2">
                      <td colspan="2" align="left" valign="middle" nowrap class="header2Bg"><?php echo getlang(465); ?> : <strong><?php echo $cabinet; ?>&nbsp;&nbsp;</strong>&nbsp;</td>
             <td height="25" align="left" valign="middle" nowrap class="header2Bg"><?php echo getlang(275); ?> : <strong><?php echo $refrapport; ?></strong></td>
           </tr>
      </table>
    <tr>
    <td>
    <table width="723" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td width="10" align=center valign="middle"  class="botBorderTdall header2Bg"><strong>N°</strong></td>
                                  <td width="264" align=right valign="middle"  class="botBorderTdall header2Bg"><div align="left"><strong><?php echo getlang(199); ?></strong> </div></td>

                                 
                                  <td width="48" align=center valign="middle"  class="botBorderTdall header2Bg"><div align="center"><strong>Réf. Lot</strong></div></td>
                                  <td width="48" align=center valign="middle"  class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(222); ?></strong></div></td>
                                  <td width="20" align=center valign="middle"  class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(204); ?></strong></div></td>
                                  <td width="48" align=center valign="middle"  class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(205); ?></strong></div></td>
                                  <td width="48" align=center valign="middle"  class="botBorderTdall header2Bg"><div align="center"><strong>Mnt total</strong></div></td>
                                
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
        <td width="30%" height="56" align="left"><div><strong><u><?php echo strtoupper($signateur1); ?></u></strong></div></td>
        <td width="30%" align="left"><div></div></td>
        <td width="30%" align="left"><div><strong><u><?php echo strtoupper($signateur2); ?></u></strong></div></td>
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
