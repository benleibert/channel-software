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
(isset($_SESSION['DATA_ETAT']['DATEJ']) 		? $libelle 		= 'Etat du stock à la date du '.$_SESSION['DATA_ETAT']['DATEJ']: $libelle ='');
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
$objPHPExcel->getActiveSheet()->setTitle('Rapport synthèse des inventaire');

//Libellé
$objPHPExcel->getActiveSheet()->setCellValue('A1', $libelle);


$ligne = 5;
//Code 	  Libellé produit 	Report 	Transfert 	Livraison 	Total 	Report 	Transfert 	Bon sortie 	Perte 	Déclass. 	Total 	Inventaire 	Stocks 	Unité
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$ligne, 'CODE PRODUIT')
            ->setCellValue('B'.$ligne, 'DESIGNATION')
			->setCellValue('C'.$ligne, 'QTE ENTREE')
			->setCellValue('D'.$ligne, 'QTE SORTIE')
			->setCellValue('E'.$ligne, 'QTE PERIMEE')
			->setCellValue('F'.$ligne, 'QTE DISPONIBLE')
			->setCellValue('G'.$ligne, 'SEUIL MIN')
			->setCellValue('H'.$ligne, 'SEUIL MAX');

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

	(isset($data[$i-1]['perte']) && $data[$i-1]['perte']!=0		? $perte		 	= $data[$i-1]['perte'] 			: $perte			='');

	(isset($data[$i-1]['qteentree']) && $data[$i-1]['qteentree']!=0 ? $qteentree		= $data[$i-1]['qteentree'] 		: $qteentree	='');
	(isset($data[$i-1]['qtesortie']) && $data[$i-1]['qtesortie']!=0 ? $qtesortie 	= $data[$i-1]['qtesortie'] 		: $qtesortie		='');
	(isset($data[$i-1]['stocks'])	? $stocks 		= $data[$i-1]['stocks'] 		: $stocks			='');
	(isset($data[$i-1]['unite']) 	? $unite 		= $data[$i-1]['unite'] 			: $unite		='');
	(isset($data[$i-1]['perime']) && $data[$i-1]['perime']!=0  			? $qteperime 	= number_format($data[$i-1]['perime'],0,'',' ') 	: $qteperime	='');
	(isset($data[$i-1]['seuilmin']) 	? $seuilmin 		= $data[$i-1]['seuilmin'] 			: $seuilmin		='');
	(isset($data[$i-1]['seuilmax']) 	? $seuilmax 		= $data[$i-1]['seuilmax'] 			: $seuilmax		='');

	//($i%2 == 0 ? $col = "tableOddRow" : $col = "tableEvenRow");

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$ligne, stripslashes($codeproduit))
            ->setCellValue('B'.$ligne, stripslashes($produit))
			->setCellValue('C'.$ligne, stripslashes($qteentree))
			->setCellValue('D'.$ligne, stripslashes($qtesortie))
			->setCellValue('E'.$ligne, stripslashes($qteperime))
			->setCellValue('F'.$ligne, stripslashes($stocks))
			->setCellValue('G'.$ligne, stripslashes($seuilmin))
			->setCellValue('H'.$ligne, stripslashes($seuilmax));

	$ligne++;
}

$fichier ='../download/Exp_ProduitACommander_'.date('YmdHis').'.xlsx';
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