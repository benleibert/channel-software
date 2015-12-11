<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//PHP Session
session_start();

(isset($_SESSION['DATA_ETAT']['exercice']) 		? $exercice 	= $_SESSION['DATA_ETAT']['exercice']: $exercice ='');
(isset($_SESSION['DATA_ETAT']['nbreLigne']) 	? $nbreLigne 	= $_SESSION['DATA_ETAT']['nbreLigne']: $nbreLigne ='');
(isset($_SESSION['DATA_ETAT']['DATEJ']) 		? $libelle 		= 'Rapport détaillé des entrées '.$_SESSION['DATA_ETAT']['DATEJ']: $libelle ='');
(isset($_SESSION['DATA_ETAT']['DATEJ']) 		? $date 		= $_SESSION['DATA_ETAT']['DATEJ']: $date ='');
(isset($_SESSION['DATA_ETAT']['ligne']) 		? $data			= $_SESSION['DATA_ETAT']['ligne'] : $data=array());



//Ajouter Class
require_once("../PHPExcel/Classes/PHPExcel.php");
require_once('../lib/phpfuncLib.php');		//All commun functions

//Création de l'objet PHPExcel
$objPHPExcel = new PHPExcel();

//Définition de la feuille active
$objPHPExcel->setActiveSheetIndex(0);

//Titre de la feuille
$objPHPExcel->getActiveSheet()->setTitle('Détails des entrées ');

//Libellé
$objPHPExcel->getActiveSheet()->setCellValue('A1', $libelle);


$ligne = 5;
//Code 	  Libellé produit 	Report 	Transfert 	Livraison 	Total 	Report 	Transfert 	Bon sortie 	Perte 	Déclass. 	Total 	Inventaire 	Stocks 	Unité
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$ligne, 'REF LOT')
            ->setCellValue('B'.$ligne, 'CODE PRODUIT')
            ->setCellValue('C'.$ligne, 'DESIGNATION')
			->setCellValue('D'.$ligne, 'DATE ENTREE')
			->setCellValue('E'.$ligne, 'DATE PEREMPTION')
			->setCellValue('F'.$ligne, 'PRIX ACHAT')
			->setCellValue('G'.$ligne, 'QTE ENTREE')
			->setCellValue('H'.$ligne, 'MONTANT TOTAL');

//$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('B'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('C'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('D'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('E'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('G'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('H'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('I'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('J'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('K'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('L'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('M'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('N'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
//$objPHPExcel->getActiveSheet()->getStyle('O'.$ligne)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


$ligne = 6;
for ($i=1; $i <= $_SESSION['DATA_ETAT']['nbreLigne']; $i++){

	(isset($data[$i-1]['reflot'])? $reflot 	= $data[$i-1]['reflot'] 	: $reflot	='');
	(isset($data[$i-1]['codeproduit'])? $codeproduit 	= $data[$i-1]['codeproduit'] 	: $codeproduit	='');
	(isset($data[$i-1]['produit']) 	? $produit 		= $data[$i-1]['produit'] 		: $produit		='');
	(isset($data[$i-1]['livraison'])		? $livraison		 	= $data[$i-1]['livraison'] 			: $livraison			='');
	(isset($data[$i-1]['recondentree'])		? $recondentree		 	= $data[$i-1]['recondentree'] 			: $recondentree			='');

	(isset($data[$i-1]['reportentree'])		? $reportentree		 	= $data[$i-1]['reportentree'] 			: $reportentree			='');
	(isset($data[$i-1]['reportsortie'])		? $reportsortie		 	= $data[$i-1]['reportsortie'] 			: $reportsortie			='');

	(isset($data[$i-1]['transfertent'])		? $transfertent		= $data[$i-1]['transfertent'] 			: $transfertent			='');
	(isset($data[$i-1]['transfertsort'])	? $transfertsort	= $data[$i-1]['transfertsort'] 			: $transfertsort			='');

	(isset($data[$i-1]['bonsortie']) && $data[$i-1]['bonsortie']!=0			? $bonsortie		= $data[$i-1]['bonsortie'] 			: $bonsortie			='');
	(isset($data[$i-1]['transfert'])  && $data[$i-1]['transfert']!=0		? $transfert		= $data[$i-1]['transfert'] 			: $transfert		='');
	(isset($data[$i-1]['recondsortie'])	 && $data[$i-1]['recondsortie']!=0	? $recondsortie	 	= $data[$i-1]['recondsortie'] 		: $recondsortie		='');
	(isset($data[$i-1]['declass'])  && $data[$i-1]['declass']!=0			? $declass		 	= $data[$i-1]['declass'] 			: $declass			='');
	(isset($data[$i-1]['ecart']) && $data[$i-1]['ecart']!=0					? $ecart		 	= $data[$i-1]['ecart'] 			: $ecart			='');

	(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

	(isset($data[$i-1]['qteentre']) && $data[$i-1]['qteentre']!=0 ? $qteentre		= $data[$i-1]['qteentre'] 		: $qteentre	='');
	(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
	(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
	(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
	(isset($data[$i-1]['dateentree']) 	? $dateentree 		= $data[$i-1]['dateentree'] 			: $dateentree		='');
	(isset($data[$i-1]['dateperemp']) 	? $dateperemp 		= $data[$i-1]['dateperemp'] 			: $dateperemp	='');
	(isset($data[$i-1]['pa'])  && $data[$i-1]['pa']						? $pa 			= number_format($data[$i-1]['pa'],0,'',' ') 		: $pa	=0);
	(isset($data[$i-1]['perime']) && $data[$i-1]['perime']!=0  			? $qteperime 	= number_format($data[$i-1]['perime'],0,'',' ') 	: $qteperime	='');

	//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

	if($dateperemp<date('Y-m-d'))  $col="tableFINIRow" ;
	$d = preg_replace('/-/','/' ,$dateperemp );
	$d = substr($d,0, 7);

	$montant = $data[$i-1]['pa'] * $data[$i-1]['qteentre'];

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$ligne, stripslashes($reflot))
            ->setCellValue('B'.$ligne, stripslashes($codeproduit))
            ->setCellValue('C'.$ligne, stripslashes($produit))
			->setCellValue('D'.$ligne, stripslashes($dateentree))
			->setCellValue('E'.$ligne, stripslashes($dateperemp))
			->setCellValue('F'.$ligne, stripslashes($pa))
			->setCellValue('G'.$ligne, stripslashes($qteentre))
			->setCellValue('H'.$ligne, stripslashes($montant));

	$ligne++;
}

$fichier ='../download/Exp_RapportStockActuel_'.date('YmdHis').'.xlsx';
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save($fichier);

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
</style></head>
<body class="bodyBg2" >
<script> writeTableStartTagBasedOnResolution(); </script>
  <tr>
    <td class="tabsBg"><script language="JavaScript" type="text/JavaScript">
</script>

<!-- End of JS code  -->
<table width="350" border="0" cellpadding="0" cellspacing="0">
  <tr>
      <td width="200" rowspan=2>
              <img src="../images/unfpa.png" alt="" width="95" height="45" hspace=2 vspace=0 />
        </td>
        <td height="24" colspan="2" align="right" valign=top>&nbsp;</td>
      </tr>
          <tr>
            <td height="20" valign="top">
            <td align="right"><!--<a href="#" onClick="doPersonalize()" >Personalize</a> |-->
           <span class="wtext">	  </span></td>
        </tr>
      </table>
    </td>
</tr>

    <tr class="searchBg">
      <td height="21" align="right">&nbsp;	 </td>
    </tr>
    <tr class=bodyBg2>
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
    <td width="*%" valign="top">


    </td>
  		 </tr>
  		 <table width="400"   border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="400" align="left" valign="top">
    <table width="400" border="0" align="left" cellpadding="1" cellspacing="1">
      <tr>
        <td height="20" colspan="2" class="leftHeader"><?php echo getlang(321); ?></td>
      </tr>
      <tr>
        <td colspan=2 align="left" valign="top" height="3"></td>
      </tr>
      <tr>
        <td nowrap>&nbsp;</td>
        </tr>
      <!-- Data Tbale contener -->
      <tr>
        <td colspan=2 align="left" valign="top"><?php echo '<a href="'.$fichier.'"><img src="../images/download.png" alt="Cliquer pour télécharger le document" hspace="0" vspace="0" /></a>'; ?>
</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>