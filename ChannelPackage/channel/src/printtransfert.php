<?php
//Session
session_start();
if($_SESSION['GL_USER']['SESSIONID']!=session_id())header("location:dbuser.php?do=logout");
require_once('../lib/phpfuncLib.php');		//All commun functions
require_once('functransfert.php');
$libel = getlang(166);
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

(isset($INFO['INF_SIGNATEUR1']) ? $magasinier = $INFO['INF_SIGNATEUR1']: $magasinier ='');
(isset($INFO['INF_SIGNATEUR2']) ? $chef = $INFO['INF_SIGNATEUR2']: $chef ='');
(isset($INFO['INF_NOMSIGNATEUR2']) ? $nomchef = $INFO['INF_NOMSIGNATEUR2']: $nomchef ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $directeur = $INFO['INF_SIGNATEUR3']: $directeur ='');
(isset($INFO['INF_NOMSIGNATEUR3']) ? $nomdirecteur = $INFO['INF_NOMSIGNATEUR3']: $nomdirecteur ='');

(isset($INFO['INF_SIGNATEUR3']) ? $gestionnaire = $INFO['INF_SIGNATEUR3']: $gestionnaire ='');
(isset($INFO['INF_SIGNATEUR4']) ? $daf = $INFO['INF_SIGNATEUR4']: $daf ='');



(isset($_SESSION['DATA_TRS']['exercice']) 		? $exercice 	= $_SESSION['DATA_TRS']['exercice']: $exercice ='');
(isset($_SESSION['DATA_TRS']['nbreLigne']) 		? $nbreLigne 	= $_SESSION['DATA_TRS']['nbreLigne']: $nbreLigne ='');
(isset($_SESSION['DATA_TRS']['service_srce']) 		? $serviceemetteur 	= getService($_SESSION['DATA_TRS']['service_srce']): $serviceemetteur ='');
(isset($_SESSION['DATA_TRS']['magasin_srce']) 		? $magsource 	= getmagasinName($_SESSION['DATA_TRS']['magasin_srce']): $magsource ='');
(isset($_SESSION['DATA_TRS']['magasin_dest']) 		? $magdest 	= getmagasinName($_SESSION['DATA_TRS']['magasin_dest']): $magdest ='');
(isset($_SESSION['DATA_TRS']['libelleetat']) && $_SESSION['DATA_TRS']['libelleetat']!=''		? $libelle = $_SESSION['DATA_TRS']['libelleetat']: $libelle =$libel.' : '.$magsource.' -> '.$magdest);

(isset($_SESSION['DATA_TRS']['codetransfert']) 		? $code 	= $_SESSION['DATA_TRS']['codetransfert']: $code ='');
(isset($_SESSION['DATA_TRS']['datetransfert']) 		? $date 	= $_SESSION['DATA_TRS']['datetransfert']: $date ='');
(isset($_SESSION['DATA_TRS']['camion']) 		? $camion 			= $_SESSION['DATA_TRS']['camion']: $camion ='');
(isset($_SESSION['DATA_TRS']['ligne']) ? $data= $_SESSION['DATA_TRS']['ligne'] : $data=array());
//Ligne
$ligne = ligneEtatTransfert($nbreLigne,$data);

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
        <td rowspan="3" align="center"><?php echo mb_strtoupper(stripslashes($nommag)); ?>, le <?php echo date("d/m/Y"); ?></td>
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
    <td>
    <!--  Libellé -->
    <table width="100%" border="0" align="left" cellpadding="1" cellspacing="1">
                <tr>
                  <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td height="20" class="leftHeader" colspan="3"><h2><?php echo $libelle; ?></h2></td>
                </tr>
                <tr>
                  <td colspan="3" >&nbsp;</td>
                </tr>
	<tr class="header2">
                      <td width="20%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;<?php echo getlang(62); ?> : <strong><?php echo $exercice; ?>&nbsp;&nbsp;</strong></td>
                      <td width="29%" class="header2Bg" align="left" valign="middle" nowrap>&nbsp;N&deg;: <strong><?php echo $code; ?>&nbsp;&nbsp;</strong></td>
          <td height="25" align="left" valign="middle" nowrap class="header2Bg">&nbsp;Date : <strong><?php echo $date; ?></strong></td>
           </tr>
                     
                     <tr class="header2">
                      <td colspan="2" align="left" valign="middle" nowrap class="header2Bg"><?php echo getlang(91); ?> : <strong><?php echo $magsource; ?>&nbsp;&nbsp;</strong><strong>&nbsp;</strong></td>
                      <td height="25" align="left" valign="middle" nowrap class="header2Bg"><?php echo getlang(90); ?> : <strong><?php echo $magdest; ?>&nbsp;</strong></td>
           </tr>
      </table>
    <p>&nbsp;</p>
  <tr>
    <td>
    <table width="723" border="0" align="left"  cellpadding="3" cellspacing="0"  class="botBorderTdall">
                              <tbody>
                                <tr align="left" valign="top" nowrap>
                                  <td align=center valign="middle" class="botBorderTdall header2Bg">&nbsp;</td>
                                  <td align=center valign="middle" class="botBorderTdall header2Bg">&nbsp;</td>
                                  <td colspan="5" align=center valign="middle" class="botBorderTdall header2Bg"><?php echo getlang(377); ?></td>
                                  <td colspan="2" align=center valign="middle" class="botBorderTdall header2Bg"><?php echo getlang(378); ?></td>
                                </tr>
                                <tr align="left" valign="top" nowrap>
                                  <td width="10" align=center valign="middle" class="botBorderTdall header2Bg"><strong>N°</strong></td>
                                  <!-- <td width="30" align=right valign="middle" nowrap class="botBorderTdall"><div align="center"><strong>Code <?php echo getlang(116); ?> </strong></div></td>
                                  <td width="100" align=right valign="middle" class="botBorderTdall"><div align="left"><strong><?php echo getlang(199); ?></strong> </div></td> -->

                                 
                                  <td width="200" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(199); ?></strong></div></td>
                                  <td width="10" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong>Qté</strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg" nowrap="nowrap"><div align="center"><strong><?php echo getlang(204); ?></strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg" nowrap="nowrap"><div align="center"><strong><?php echo getlang(205); ?></strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg" nowrap="nowrap"><div align="center"><strong>Mnt total</strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg" nowrap="nowrap"><div align="center"><strong>Réf. Lot</strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(200); ?></strong></div></td>
                                  <td width="20" align=center valign="middle" class="botBorderTdall header2Bg"><div align="center"><strong><?php echo getlang(204); ?></strong></div></td>
                                
                                </tr>
                                <?php echo $ligne; ?>
        </tbody>
      </table>
      </td>
  </tr>
  <tr><td>
    <p>&nbsp;</p></td>
  </tr>
 
  
</table>
</body>
</html>
