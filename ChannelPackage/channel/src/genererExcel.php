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

$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

//Création de l'objet PHPExcel
$objPHPExcel = new PHPExcel();

//Définition de la feuille active
$objPHPExcel->setActiveSheetIndex(0);

//Titre de la feuille
$objPHPExcel->getActiveSheet()->setTitle('Indicateurs clés '.$_SESSION['DATA']['DEBUT'].' à '.$_SESSION['DATA']['FIN']);

//Données de la cellule A1
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Indicateurs clés '.$_SESSION['DATA']['DEBUT'].' à '.$_SESSION['DATA']['FIN']);


if($_SESSION['DATA']['CHOIX']==2){//Par REGION
	//Pour chaque région

	$ligne = 3;
	foreach ($_SESSION['DATA']['REGION']  as $key => $val) {
		$ligne1 =$ligne+2;
		// Saisie de plusieurs cellules en une instruction
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$ligne, 'REGION : '.$val)
		            ->setCellValue('A'.$ligne1, 'N°')
		            ->setCellValue('B'.$ligne1, 'Indicateur');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne1)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$ligne1)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


		$i=2;
		foreach ($_SESSION['DATA']['ANNEE']  as $keyAnnee => $valAnnee) {
			$IndT =$alpha[$i].$ligne1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("$IndT", $valAnnee);
			$objPHPExcel->getActiveSheet()->getStyle("$IndT")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$i++;
		}



		$i=1; //Numéro
		$j=3+$ligne; //Ligne 4 de la feuille excel
		$k=0;
		reset($_SESSION['DATA']['INDICATEUR']);
		foreach ($_SESSION['DATA']['INDICATEUR']  as $keyIndicat => $Indicat) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j, $i)
				->setCellValue('B'.$j, $Indicat);

			//INDICATEUR CLE
			$k=3;
			reset($_SESSION['DATA']['ANNEE']);
			foreach ($_SESSION['DATA']['ANNEE'] as $keyAnn => $Ann ) {
				(isset($_SESSION['DATA']['DONNEES'][$key][$Ann][$keyIndicat]) ? $v = preg_replace('/\./',',' ,$_SESSION['DATA']['DONNEES'][$key][$Ann][$keyIndicat]) : $v='');
				$ind = $alpha[$k].$j;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("$ind", $v);
				$k++;
			}
			$i++;
			$j++;
		}
		$ligne= $ligne + 5 + count($_SESSION['DATA']['INDICATEUR']);
	}//FIN REGION

}
elseif($_SESSION['DATA']['CHOIX']==1){ //Par INDICATEUR
	//Pour chaque région

	$ligne = 3;
	foreach ($_SESSION['DATA']['INDICATEUR']  as $key => $val) {
		$ligne1 =$ligne+2;
		// Saisie de plusieurs cellules en une instruction
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$ligne, 'INDICATEUR : '.$val)
		            ->setCellValue('A'.$ligne1, 'N°')
		            ->setCellValue('B'.$ligne1, 'Région');
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ligne1)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$ligne1)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


		$i=2;
		foreach ($_SESSION['DATA']['ANNEE']  as $keyAnnee => $valAnnee) {
			$IndT =$alpha[$i].$ligne1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("$IndT", $valAnnee);
			$objPHPExcel->getActiveSheet()->getStyle("$IndT")->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
			$i++;
		}



		$i=1; //Numéro
		$j=3+$ligne; //Ligne 4 de la feuille excel
		$k=0;
		reset($_SESSION['DATA']['REGION']);
		foreach ($_SESSION['DATA']['REGION']  as $keyReg => $Reg) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j, $i)
				->setCellValue('B'.$j, $Reg);

			//INDICATEUR CLE
			$k=3;
			reset($_SESSION['DATA']['ANNEE']);
			foreach ($_SESSION['DATA']['ANNEE'] as $keyAnn => $Ann ) {
				(isset($_SESSION['DATA']['DONNEES'][$key][$Ann][$keyReg]) ? $v = preg_replace('/\./',',' ,$_SESSION['DATA']['DONNEES'][$key][$Ann][$keyReg]) : $v='');
				$ind = $alpha[$k].$j;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("$ind", $v);
				$k++;
			}
			$i++;
			$j++;
		}
		$ligne= $ligne + 5 + count($_SESSION['DATA']['REGION']);
	}//FIN REGION
}


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter->save('Indicateurs_Cles_'.$_SESSION['DATA']['DEBUT'].$_SESSION['DATA']['FIN'].'.xlsx');

echo '<a href="Indicateurs_Cles_'.$_SESSION['DATA']['DEBUT'].$_SESSION['DATA']['FIN'].'.xlsx"><img src="webadmin/images/download.png" alt="Cliquer pour télécharger le document" hspace="0" vspace="0" /></a>'
?>