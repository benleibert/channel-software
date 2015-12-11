<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

//PHP Session
session_start();

//Ajouter Class
require("PHPExcel/Classes/PHPExcel.php");
require_once('HTML2PDF/html2pdf.class.php');
require_once('funcdonnees.php');

if($_SESSION['DATA']['CHOIX']==2){//Par REGION
	//Pour chaque région
	$affichage = ligneConDonneesParRegion2($_SESSION['DATA']);
	$html2pdf = new HTML2PDF('L','A4','fr');
	$html2pdf->WriteHTML($affichage);
	$html2pdf->Output('IndicateursCles.pdf');

}
elseif($_SESSION['DATA']['CHOIX']==1){ //Par INDICATEUR

    $affichage = ligneConDonneesParIndicateur2($_SESSION['DATA']);
	$html2pdf = new HTML2PDF('L','A4','fr');
	$html2pdf->WriteHTML($affichage);
	$html2pdf->Output('IndicateursCles.pdf');
}
?>