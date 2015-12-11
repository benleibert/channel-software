<?php
/**
 *
 * @version $Id$
 * @copyright 2011
 * KG
 * Gestion de la connexion, déconnexion
 */

//PHP Session
session_start();

//MySQL Parameters
require_once('../lib/global.inc');

//PHP functions librairy
require_once('../lib/phpfuncLib.php');

//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
	switch($do){
		//Log in User
		case 'next':
			(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice = '');
			(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
			(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  ? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
			(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
			(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
			(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut = '0');

			//Data
			$_SESSION['DATA_BDS']=array(
			'exercice'=>$exercice,
			'datebonsortie'=>$datebonsortie,
			'idbeneficiaire'=>$idbeneficiaire,
			'beneficiaire'=>$beneficiaire,
			'refbonsortie'=>$refbonsortie,
			'libelle'=>$libelle,
			'nbreLigne'=>$nbreLigne
			);

			$_SESSION['GL_USER']['JOUR'] = mysqlFormat($datebonsortie);

			//Etape 2
			header('location:addbonsortie1.php?selectedTab=bds');
			break;

		case 'add':
			(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice = '');
			(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
			(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  	? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
			(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
			(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
			(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut = '0');

			$datebonsortie = mysqlFormat($datebonsortie);
			$magasin = $_SESSION['GL_USER']['MAGASIN'];
			$exercice =  $_SESSION['GL_USER']['EXERCICE'];
			//$statut=1 ; //Validation automatique

			$numauto = myDbLastId('bonsortie', 'ID_BONSORTIE', $magasin)+1;
			$codeBon = "$numauto/$magasin";

			//Data
			$_SESSION['DATA_BDS']=array(
			'exercice'=>$exercice,
			'datebonsortie'=>$datebonsortie,
			'idbeneficiaire'=>$idbeneficiaire,
			'beneficiaire'=>$beneficiaire,
			'refbonsortie'=>$refbonsortie,
			'libelle'=>$libelle,
			'nbreLigne'=>$nbreLigne
			);

			//Insert
			$sql  = "INSERT INTO `bonsortie` (CODE_BONSORTIE, ID_BONSORTIE, REF_BONSORTIE, `CODE_BENEF`, `CODE_MAGASIN`, `ID_EXERCICE`,  `SOR_LIBELLE`,
			`SOR_DATE`, `SOR_VALIDE`, SOR_DATEVALID)  VALUES ('".addslashes($codeBon)."', '".addslashes($numauto)."', '".addslashes($refbonsortie)."','".addslashes($idbeneficiaire)."',
			'".addslashes($magasin)."',	'".addslashes($exercice)."','".addslashes($libelle)."', '".addslashes($datebonsortie)."','$statut','".date('Y-m-d H:i:s')."');";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un bon de sortie ($codeBon, Bénéficiaire :$beneficiaire/$refbonsortie)"); //updateLog($username, $idcust, $action='' )

			//Collect Data
			$sql1 ="";
			$sql2 ="";
			$sql3 ="";
			$numautoDetbon = myDbLastId('detbonsortie', 'ID_DETBONSORTIE', $magasin);
			$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

			$_SESSION['DATA_BDS']['ligne'] =array();
			for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
				(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
				(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
				(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i] 	? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
				(isset($_POST['produit'.$i]) && $_POST['produit'.$i]			? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
				(isset($_POST['qte'.$i]) && $_POST['qte'.$i]					? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
				(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
				(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
				(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
				(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

				if($codeproduit!='' && $produit!='' && $qte!='') {
					$numautoDetbon++;
					$codeDetBon = "$numautoDetbon/$magasin";

					$sql1 .="INSERT INTO `detbonsortie` (CODE_DETBONSORTIE, ID_DETBONSORTIE, CODE_BONSORTIE, CODE_MAGASIN, `CODE_PRODUIT`, `BSPRD_QTE`, `BSPRD_RECU`,
					`BSPRD_UNITE`,BSPRD_REFLOT, BSPRD_DATEPEREMP, `BSPRD_PV`, BSPRD_MONLOT)
					VALUES ('".addslashes($codeDetBon)."', '".addslashes($numautoDetbon)."', '".addslashes($codeBon)."', '".addslashes($magasin)."','".addslashes($codeproduit)."',
					'".addslashes($qte)."' ,	'".addslashes($qte)."' ,'".addslashes($unite)."',	'".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."',
					'".addslashes($prix)."', '".addslashes($monlot)."'); ";

					$numautoMvt++;
					$codeMvt = "$numautoMvt/$magasin";

					$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
					`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
					`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`)
						VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."','".addslashes($codeproduit)."',	'".addslashes($magasin)."',
					'".addslashes($numautoMvt)."', '".addslashes($codeBon)."', '".addslashes($datebonsortie)."' ,'".addslashes(date('H:i:s'))."' ,
					'".addslashes($qte)."' ,	'".addslashes($unite)."', 'BON DE SORTIE', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."',
					'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
				}
			}

			if (($sql1 !='')) {
				$query =  $cnx->prepare($sql1); //Prepare the SQL
				$query->execute(); //Execute prepared SQL =>
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes du bon de sortie($codeBon, Bénéficiaire :$beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )

				$query =  $cnx->prepare($sql2); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un mouvement($codeBon, Bénéficiaire $beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )
			}
			//echo $sql, $sql1, $sql2;
			unset($_SESSION['DATA_BDS']);
			header('location:bonsortie.php?selectedTab=bds&rst=1');
			break;

		case 'update':
			(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 			= trim($_POST['xid']) 			: $xid = '');
			(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice = '');
			(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
			(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  ? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
			(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
			(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
			(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut = '0');

			$datebonsortie = mysqlFormat($datebonsortie);
			$magasin = $_SESSION['GL_USER']['MAGASIN'];
			$exercice =  $_SESSION['GL_USER']['EXERCICE'];
			//$statut = 1;

			//Insert
			$sql  = "UPDATE `bonsortie` SET `CODE_BENEF`='".addslashes($idbeneficiaire)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,
			`REF_BONSORTIE`='".addslashes($refbonsortie)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`SOR_DATE`='".addslashes($datebonsortie)."' ,
			`SOR_LIBELLE`= '".addslashes($libelle)."',	`SOR_VALIDE`='$statut', SOR_DATEVALID='".date('Y-m-d')."'
			WHERE CODE_BONSORTIE LIKE '".addslashes($xid)."'";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un bon de sortie ($xid, Bénéficiaire :$beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )

			//Data
			$_SESSION['DATA_BDS']['exercice']=$exercice;
			$_SESSION['DATA_BDS']['datebonsortie']=$datebonsortie;

			//Collect Data
			$sql1 ="";
			$sql2 ="";
			$numautoDetbon = myDbLastId('detbonsortie', 'ID_DETBONSORTIE', $magasin);
			$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

			$_SESSION['DATA_BDS']['ligne'] =array();
			for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
				(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
				(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
				(isset($_POST['oldcodeproduit'.$i]) && $_POST['oldcodeproduit'.$i]	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
				(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i] 		? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
				(isset($_POST['produit'.$i]) && $_POST['produit'.$i]				? $produit 			= $_POST['produit'.$i] 		: $produit 		= '');
				(isset($_POST['qte'.$i]) && $_POST['qte'.$i]						? $qte 				= $_POST['qte'.$i] 			: $qte 			= '');
				(isset($_POST['unite'.$i]) && $_POST['unite'.$i]					? $unite 			= $_POST['unite'.$i] 		: $unite 		= '');
				(isset($_POST['prix'.$i]) && $_POST['prix'.$i]						? $prix 			= $_POST['prix'.$i] 		: $prix 		= '');
				(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]					? $reflot			= $_POST['reflot'.$i] 		: $reflot 		= '');
				(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]			? $dateperemp 		= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

				if($code_detbonsortie!='' && $oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
					$sql1 .="UPDATE `detbonsortie` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`BSPRD_QTE`='".addslashes($qte)."' ,`BSPRD_RECU`='".addslashes($qte)."' ,
					`BSPRD_UNITE`='".addslashes($unite)."', `BSPRD_PV`='".addslashes($prix)."', `BSPRD_MONLOT`='".addslashes($monlot)."' , CODE_MAGASIN ='".addslashes($magasin)."'
					WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND CODE_BONSORTIE LIKE '".addslashes($xid)."' AND `CODE_DETBONSORTIE`='".addslashes($code_detbonsortie)."'; ";

					$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
					`MVT_DATE`='".addslashes($datebonsortie)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,`MVT_QUANTITE`='".addslashes($qte)."' ,
					`MVT_UNITE`='".addslashes($unite)."',`MVT_VALID`='$statut', `MVT_TYPE`='S',`MVT_PV`='".addslashes($prix)."',   MVT_MONLOT='".addslashes($monlot)."'
					WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='BON DE SORTIE' AND ID_SOURCE LIKE '".addslashes($xid)."' AND `MVT_TYPE`='S'; ";
				}
				elseif($code_detbonsortie=='' && $oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!='') {

					$numautoDetbon++;
					$codeDetBon = "$numautoDetbon/$magasin";

					$sql1 .="INSERT INTO `detbonsortie` (CODE_DETBONSORTIE, ID_DETBONSORTIE, CODE_BONSORTIE, `CODE_PRODUIT`, `BSPRD_QTE`, `BSPRD_RECU`,
					`BSPRD_UNITE`,BSPRD_REFLOT, BSPRD_DATEPEREMP, `BSPRD_PV`, BSPRD_MONLOT, `CODE_MAGASIN`)
					VALUES ('".addslashes($codeDetBon)."', '".addslashes($numautoDetbon)."', '".addslashes($xid)."','".addslashes($codeproduit)."',
					'".addslashes($qte)."' ,	'".addslashes($qte)."' ,'".addslashes($unite)."',	'".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."',
					'".addslashes($prix)."', '".addslashes($monlot)."', '".addslashes($magasin)."'); ";

					$numautoMvt++;
					$codeMvt = "$numautoMvt/$magasin";

					 $sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
					`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
					`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."','".addslashes($codeproduit)."',
					'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', 	'".addslashes($xid)."',  '".addslashes($datebonsortie)."' ,'".addslashes(date('H:i:s'))."' ,
					'".addslashes($qte)."' ,'".addslashes($unite)."', 'BON DE SORTIE','$statut','".date('Y-m-d H:i:s')."', 'S', '".addslashes($reflot)."' ,
					'".addslashes(mysqlFormat($dateperemp))."',	'".addslashes($prix)."', '".addslashes($monlot)."') ; ";
				}
			}

			if (($sql1 !='')) {
				$query =  $cnx->prepare($sql1); //Prepare the SQL
				$query->execute(); //Execute prepared SQL =>
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de bonsortie('.$xid.', Bénéficiare :'.$beneficiaire.' / '.$refbonsortie.')'); //updateLog($username, $idcust, $action='' )

				$query =  $cnx->prepare($sql2); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement(".$xid.', Bénéficiare :'.$beneficiaire.' / '.$refbonsortie.')'); //updateLog($username, $idcust, $action='' )
			}
			//echo $sql, $sql1, $sql2;
			unset($_SESSION['DATA_BDS']);
			header('location:bonsortie.php?selectedTab=bds&rs=2');
			break;

		case 'validate':
			(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 				= trim($_POST['xid']) 			: $xid = '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
			(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  	? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');

			//Validate
			$sql  = "UPDATE `bonsortie` SET `SOR_VALIDE`='1' , `SOR_DATEVALID`='".date('Y-m-d H:i:s')."' WHERE CODE_BONSORTIE LIKE '".addslashes($xid)."';";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un bon de sortie (".$xid.', bénéficiaire :'.$beneficiaire.')'); //updateLog($username, $idcust, $action='' )

			//Collect Data
			$sql1 ="";

			for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
				(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
				(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
				(isset($_POST['oldcodeproduit'.$i]) && $_POST['oldcodeproduit'.$i]	? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
				(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i] 		? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
				(isset($_POST['produit'.$i]) && $_POST['produit'.$i]				? $produit 			= $_POST['produit'.$i] 		: $produit 		= '');
				(isset($_POST['qte'.$i]) && $_POST['qte'.$i]						? $qte 				= $_POST['qte'.$i] 			: $qte 			= $qte);
				(isset($_POST['unite'.$i]) && $_POST['unite'.$i]					? $unite 			= $_POST['unite'.$i] 		: $unite 		= '');
				(isset($_POST['prix'.$i]) && $_POST['prix'.$i]						? $prix 			= $_POST['prix'.$i] 		: $prix 		= '');
				(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]					? $reflot			= $_POST['reflot'.$i] 		: $reflot 		= '');
				(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]			? $dateperemp 		= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

				$dateperemp = preg_replace('[\/]','-',$dateperemp);
				$dateperemp =mysqlFormat($dateperemp);

				if($codeproduit!='' && $produit!='' && $qte!='') {
					$sql1 .="UPDATE `mouvement` SET `MVT_VALID`='1',`MVT_TYPE`='S', `MVT_DATEVALID`='".date('Y-m-d H:i:s')."'
					WHERE `CODE_PRODUIT`='".addslashes($codeproduit)."' AND `MVT_NATURE`='BON DE SORTIE' AND ID_SOURCE LIKE '".addslashes($xid)."'
					AND `MVT_TYPE` LIKE 'S' AND MVT_MONLOT LIKE '".addslashes($monlot)."'; ";
				}
			}

			if (($sql1 !='')) {
				$query =  $cnx->prepare($sql1); //Prepare the SQL
				$query->execute(); //Execute prepared SQL =>
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'un mouvement($xid, Bénéficiaire $beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )
			}
			unset($_SESSION['DATA_BDS']);
			header('location:bonsortie.php?selectedTab=bds&rs=2');
			break;

		case 'detail':
			(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			//COMMANDE
			$sql = "SELECT bonsortie.*, beneficiaire.CODE_BENEF, beneficiaire.BENEF_NOM FROM  `bonsortie`
			INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF)
			WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_BONSORTIE` LIKE '".addslashes($id)."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$row = $query->fetch(PDO::FETCH_ASSOC);

			//Data  CDE_STATUT
			$_SESSION['DATA_BDS']=array(
			'xid'=>$row['CODE_BONSORTIE'],
			'exercice'=>$row['ID_EXERCICE'],
			'datebonsortie'=>frFormat2($row['SOR_DATE']),
			'refbonsortie'=>stripslashes($row['REF_BONSORTIE']),
			'beneficiaire'=>stripslashes($row['BENEF_NOM']),
			'libelle'=>stripslashes($row['SOR_LIBELLE']),
			'statut'=>$row['SOR_VALIDE'],
			'nbreLigne'=>0
			);

			//LIGNES COMMANDE
			$sql = "SELECT detbonsortie.* ,produit.PRD_LIBELLE  FROM `detbonsortie` INNER JOIN produit ON (detbonsortie.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE CODE_BONSORTIE LIKE '".addslashes($id)."' ORDER BY detbonsortie.CODE_PRODUIT ASC;";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Ligne
			$_SESSION['DATA_BDS']['ligne'] =array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$row['CODE_DETBONSORTIE'],'monlot'=>$row['BSPRD_MONLOT'], 'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['BSPRD_QTE'],'unite'=>$row['BSPRD_UNITE'],'prix'=>$row['BSPRD_PV'], 'reflot'=>$row['BSPRD_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['BSPRD_DATEPEREMP']))));
			}
			$_SESSION['DATA_BDS']['nbreLigne'] = $query->rowCount();
			header('location:detailbonsortie.php?selectedTab=bds&rst=1');
			break;

		case 'journal':
			(isset($_GET['xid']) ? $id = $_GET['xid'] : $id ='');
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			//COMMANDE
			$sql = "SELECT bonsortie.*, beneficiaire.CODE_BENEF, beneficiaire.BENEF_NOM FROM  `bonsortie` INNER JOIN beneficiaire
			ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF) WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."'
			AND `CODE_BONSORTIE` LIKE '".addslashes($id)."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$row = $query->fetch(PDO::FETCH_ASSOC);

			//Data  CDE_STATUT
			$_SESSION['DATA_BDS']=array(
			'xid'=>$row['CODE_BONSORTIE'],
			'exercice'=>$row['ID_EXERCICE'],
			'datebonsortie'=>frFormat2($row['SOR_DATE']),
			'refbonsortie'=>stripslashes($row['REF_BONSORTIE']),
			'beneficiaire'=>stripslashes($row['BENEF_NOM']),
			'libelle'=>stripslashes($row['SOR_LIBELLE']),
			'statut'=>$row['SOR_VALIDE'],
			'nbreLigne'=>0,
			'ligne'=>array(),
			'journal'=>array()
			);

			//LIGNES BON SORTIE
			$sql = "SELECT detbonsortie.* ,produit.PRD_LIBELLE  FROM `detbonsortie`
			INNER JOIN produit ON (detbonsortie.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE CODE_BONSORTIE LIKE '".addslashes($id)."'  ORDER BY detbonsortie.CODE_PRODUIT ASC;";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Ligne
			$_SESSION['DATA_BDS']['ligne'] =array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$row['CODE_DETBONSORTIE'], 'monlot'=>$row['BSPRD_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['BSPRD_QTE'], 'qtelvr'=>$row['BSPRD_RECU'], 'unite'=>$row['BSPRD_UNITE'],'prix'=>$row['BSPRD_PV'], 'reflot'=>$row['BSPRD_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['BSPRD_DATEPEREMP']))));
			}
			$_SESSION['DATA_BDS']['nbreLigne'] = $query->rowCount();


			//LIGNES MOUVEMENT
			$sql = "SELECT mouvement.*, produit.PRD_LIBELLE FROM `mouvement` INNER JOIN produit ON (mouvement.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
			WHERE MVT_NATURE LIKE 'BON DE SORTIE' AND ID_SOURCE LIKE '".addslashes($id)."' ORDER BY CODE_PRODUIT ASC";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Ligne
			$_SESSION['DATA_BDS']['journal'] =array();
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				array_push($_SESSION['DATA_BDS']['journal'], array('monlot'=>$row['MVT_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'],'prix'=>$row['MVT_PV']));
			}

			$_SESSION['DATA_BDS']['nbreLigne2'] = $query->rowCount();
			header('location:journalbonsortie.php?selectedTab=bds&rst=1');
			break;

		case 'check':
			$msg = "";
			(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

			if($code !=''){
				$sql = "SELECT COUNT(CODE_BONSORTIE) AS NBRE FROM  `bonsortie` WHERE `REF_BONSORTIE` LIKE '".addslashes($code)."'";
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				$row = $query->fetch(PDO::FETCH_ASSOC);

				if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code.';}
			}
			echo $msg;
			break;

		case 'sendDate':
			$msg = "";
			(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

			if($code !=''){
				$_SESSION['GL_USER']['JOUR']= mysqlFormat($code);
			}
			echo 1;
			break;

		default : ///Nothing
			//header('location:../../index.php');
	}
}
elseif($myaction !='')
switch($myaction){

	case 'addline':
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie 	= '');
		(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  ? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie 	= '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire	= '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid				= '');

		//Data
		$_SESSION['DATA_BDS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datebonsortie'=>$datebonsortie,
		'idbeneficiaire'=>$idbeneficiaire,
		'beneficiaire'=>$beneficiaire,
		'refbonsortie'=>$refbonsortie,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_BDS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
			(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 	? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) ? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_BDS']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_BDS']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$code_detbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit,'oldcodeproduit'=>$oldcodeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
		}
		//Add line
		$_SESSION['DATA_BDS']['nbreLigne'] +=1;
		$_SESSION['DATA_BDS']['nbreLigne'] -=$moins;
		header('location:addbonsortie1.php?selectedTab=bds');
		break;

	case 'addline1':
			(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice = '');
			(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
			(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  	? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');
			(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
			(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
			(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
			(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
			(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut = '0');
		$_SESSION['DATA_BDS']['nbreLigne'] +=1;
		$_SESSION['DATA_BDS']['nbreLigne'] -=$moins;

			$datebonsortie = mysqlFormat($datebonsortie);
			$magasin = $_SESSION['GL_USER']['MAGASIN'];
			$exercice =  $_SESSION['GL_USER']['EXERCICE'];
			//$statut=1 ; //Validation automatique

			$numauto = myDbLastId('bonsortie', 'ID_BONSORTIE', $magasin)+1;
			$codeBon = "$numauto/$magasin";

			//Data
			$_SESSION['DATA_BDS']=array(
			'exercice'=>$exercice,
			'datebonsortie'=>$datebonsortie,
			'idbeneficiaire'=>$idbeneficiaire,
			'beneficiaire'=>$beneficiaire,
			'refbonsortie'=>$refbonsortie,
			'libelle'=>$libelle,
			'nbreLigne'=>$nbreLigne
			);

			//Insert
			$sql  = "INSERT INTO `bonsortie` (CODE_BONSORTIE, ID_BONSORTIE, REF_BONSORTIE, `CODE_BENEF`, `CODE_MAGASIN`, `ID_EXERCICE`,  `SOR_LIBELLE`,
			`SOR_DATE`, `SOR_VALIDE`, SOR_DATEVALID)  VALUES ('".addslashes($codeBon)."', '".addslashes($numauto)."', '".addslashes($refbonsortie)."','".addslashes($idbeneficiaire)."',
			'".addslashes($magasin)."',	'".addslashes($exercice)."','".addslashes($libelle)."', '".addslashes($datebonsortie)."','$statut','".date('Y-m-d H:i:s')."');";

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un bon de sortie ($codeBon, Bénéficiaire :$beneficiaire/$refbonsortie)"); //updateLog($username, $idcust, $action='' )

			//Collect Data
			$sql1 ="";
			$sql2 ="";
			$sql3 ="";
			$numautoDetbon = myDbLastId('detbonsortie', 'ID_DETBONSORTIE', $magasin);
			$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

			$_SESSION['DATA_BDS']['ligne'] =array();
			for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
				(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
				(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
				(isset($_POST['codeproduit'.$i]) && $_POST['codeproduit'.$i] 	? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
				(isset($_POST['produit'.$i]) && $_POST['produit'.$i]			? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
				(isset($_POST['qte'.$i]) && $_POST['qte'.$i]					? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
				(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
				(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
				(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
				(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

				if($codeproduit!='' && $produit!='' && $qte!='') {
					$numautoDetbon++;
					$codeDetBon = "$numautoDetbon/$magasin";

					$sql1 .="INSERT INTO `detbonsortie` (CODE_DETBONSORTIE, ID_DETBONSORTIE, CODE_BONSORTIE, CODE_MAGASIN, `CODE_PRODUIT`, `BSPRD_QTE`, `BSPRD_RECU`,
					`BSPRD_UNITE`,BSPRD_REFLOT, BSPRD_DATEPEREMP, `BSPRD_PV`, BSPRD_MONLOT)
					VALUES ('".addslashes($codeDetBon)."', '".addslashes($numautoDetbon)."', '".addslashes($codeBon)."', '".addslashes($magasin)."','".addslashes($codeproduit)."',
					'".addslashes($qte)."' ,	'".addslashes($qte)."' ,'".addslashes($unite)."',	'".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."',
					'".addslashes($prix)."', '".addslashes($monlot)."'); ";

					$numautoMvt++;
					$codeMvt = "$numautoMvt/$magasin";

					$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
					`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
					`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`)
						VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."','".addslashes($codeproduit)."',	'".addslashes($magasin)."',
					'".addslashes($numautoMvt)."', '".addslashes($codeBon)."', '".addslashes($datebonsortie)."' ,'".addslashes(date('H:i:s'))."' ,
					'".addslashes($qte)."' ,	'".addslashes($unite)."', 'BON DE SORTIE', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."',
					'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
				}
			}

			if (($sql1 !='')) {
				$query =  $cnx->prepare($sql1); //Prepare the SQL
				$query->execute(); //Execute prepared SQL =>
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes du bon de sortie($codeBon, Bénéficiaire :$beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )

				$query =  $cnx->prepare($sql2); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
				updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un mouvement($codeBon, Bénéficiaire $beneficiaire / $refbonsortie)"); //updateLog($username, $idcust, $action='' )
			}
		header('location:editbonsortie.php?selectedTab=bds');
		break;

	case 'delline':
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie 	= '');
		(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  ? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie 	= '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire 	= '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire		= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection 	= trim($_POST['rowSelection']) 	: $rowSelection 	= '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid				= '');

		$supp =0;
		//Data
		$_SESSION['DATA_BDS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datebonsortie'=>$datebonsortie,
		'idbeneficiaire'=>$idbeneficiaire,
		'beneficiaire'=>$beneficiaire,
		'refbonsortie'=>$refbonsortie,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_BDS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
			(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 	? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) ? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 		= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$code_detbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit,'oldcodeproduit'=>$oldcodeproduit,  'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_BDS']['nbreLigne'] -=$supp;
		header('location:addbonsortie1.php?selectedTab=bds');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
		(isset($_POST['refbonsortie']) && $_POST['refbonsortie']!=''  ? $refbonsortie 	= trim($_POST['refbonsortie'])	: $refbonsortie = '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_BDS']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datebonsortie'=>$datebonsortie,
		'idbeneficiaire'=>$idbeneficiaire,
		'beneficiaire'=>$beneficiaire,
		'refbonsortie'=>$refbonsortie,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_BDS']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_BDS']['nbreLigne']; $i++){
			(isset($_POST['code_detbonsortie'.$i]) && $_POST['code_detbonsortie'.$i] 	? $code_detbonsortie 	= $_POST['code_detbonsortie'.$i] 	: $code_detbonsortie 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) 		? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) 	? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) ? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 		= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$code_detbonsortie,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit,'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){
				$supp++;
				try {
					$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
				}
				catch (PDOException $error) { //Treat error
					//("Erreur de connexion : " . $error->getMessage() );
					header('location:errorPage.php');
				}
				$sql = "DELETE FROM  `detbonsortie` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `ID_DETBONSORTIE` = '".addslashes($code_detbonsortie)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'BON DE SORTIE'
				AND `ID_SOURCE` = '".addslashes($xid)."' AND MVT_MONLOT LIKE '".addslashes($monlot)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_BDS']['nbreLigne'] -=$supp;
		header('location:editbonsortie.php?selectedTab=bds');
		break;

	case 'edit':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//COMMANDE
		$sql = "SELECT bonsortie.*, beneficiaire.CODE_BENEF, beneficiaire.BENEF_NOM FROM  `bonsortie` INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF)
		WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_BONSORTIE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_BDS']=array(
		'xid'=>$row['CODE_BONSORTIE'],
		'exercice'=>$row['ID_EXERCICE'],
		'datebonsortie'=>frFormat2($row['SOR_DATE']),
		'refbonsortie'=>stripslashes($row['REF_BONSORTIE']),
		'idbeneficiaire'=>stripslashes($row['CODE_BENEF']),
		'beneficiaire'=>stripslashes($row['BENEF_NOM']),
		'libelle'=>stripslashes($row['SOR_LIBELLE']),
		'statut'=>$row['SOR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT detbonsortie.* ,produit.PRD_LIBELLE FROM `detbonsortie` INNER JOIN produit
		ON (detbonsortie.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE CODE_BONSORTIE LIKE '".addslashes($split[0])."'  ORDER BY ID_DETBONSORTIE ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		$whereAll="  AND mouvement.CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND mouvement.ID_EXERCICE='".$_SESSION['GL_USER']['EXERCICE']."' ";


		//Ligne
		$_SESSION['DATA_BDS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$res = ProduitStockDispo($row['BSPRD_REFLOT'], $whereAll);
			$dispo = $res + $row['BSPRD_QTE'];
			array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$row['CODE_DETBONSORTIE'], 'monlot'=>$row['BSPRD_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'oldcodeproduit'=>$row['CODE_PRODUIT'],'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['BSPRD_QTE'],  'dispo'=>$dispo, 'unite'=>$row['BSPRD_UNITE'],'prix'=>$row['BSPRD_PV'], 'reflot'=>$row['BSPRD_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['BSPRD_DATEPEREMP']))));
		}
		$_SESSION['DATA_BDS']['nbreLigne'] = $query->rowCount();
		header('location:editbonsortie.php?selectedTab=bds&rst=1');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		(isset($_POST['oldcode']) ? $oldcode = $_POST['oldcode'] : $oldcode ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "UPDATE `bonsortie` SET  SOR_VALIDE=2, SOR_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."' WHERE `CODE_BONSORTIE` = '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2, MVT_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."'  WHERE (MVT_NATURE LIKE 'BON DE SORTIE') AND ID_SOURCE LIKE '".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Annulation d\'un bon de sortie ($xid, $oldcode)"); //updateLog($username, $idcust, $action='' )
		//echo $sql;
		header('location:bonsortie.php?selectedTab=bds&rst=1');
		break;

	case 'validate':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/@/',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//COMMANDE
		$sql = "SELECT bonsortie.*, beneficiaire.CODE_BENEF, beneficiaire.BENEF_NOM FROM  `bonsortie` INNER JOIN beneficiaire ON (bonsortie.CODE_BENEF=beneficiaire.CODE_BENEF)
		WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `CODE_BONSORTIE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_BDS']=array(
		'xid'=>$row['CODE_BONSORTIE'],
		'exercice'=>$row['ID_EXERCICE'],
		'datebonsortie'=>frFormat2($row['SOR_DATE']),
		'refbonsortie'=>stripslashes($row['REF_BONSORTIE']),
		'idbeneficiaire'=>$row['CODE_BENEF'],
		'beneficiaire'=>stripslashes($row['BENEF_NOM']),
		'libelle'=>stripslashes($row['SOR_LIBELLE']),
		'statut'=>$row['SOR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES
		$sql = "SELECT * FROM `detbonsortie` INNER JOIN produit ON (detbonsortie.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE CODE_BONSORTIE LIKE '".addslashes($split[0])."'  ORDER BY ID_DETBONSORTIE ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_BDS']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$dispo= ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='E')- ProduitsQte($row['CODE_PRODUIT'], $valid=1, $type='S');
			array_push($_SESSION['DATA_BDS']['ligne'], array('code_detbonsortie'=>$row['CODE_DETBONSORTIE'], 'monlot'=>$row['BSPRD_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['BSPRD_QTE'], 'dispo'=>$dispo, 'unite'=>$row['BSPRD_UNITE'], 'magasin'=>$row['CODE_MAGASIN'],'prix'=>$row['BSPRD_PV'],'reflot'=>$row['BSPRD_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['BSPRD_DATEPEREMP']))));
		}
		$_SESSION['DATA_BDS']['nbreLigne'] = $query->rowCount();
		header('location:validbonsortie.php?selectedTab=bds&rst=1');
		break;

	case 'delete':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'] : $id =array());
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		foreach($id as $key => $val){
			$split = preg_split('/@/',$val);
			$sql = "DELETE FROM  `detbonsortie` WHERE `CODE_BONSORTIE` LIKE '".addslashes($split[0])."';
			DELETE FROM  `bonsortie` WHERE `CODE_BONSORTIE` LIKE '".addslashes($split[0])."';";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` LIKE '".addslashes($split[0])."' AND MVT_NATURE LIKE 'BON DE SORTIE'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:bonsortie.php?selectedTab=bds&rs=4');
		break;

	default : ///Nothing
		//header('location:../../index.php');
}
elseif($myaction =='' && $do ='') header('location:../../index.php');
?>
