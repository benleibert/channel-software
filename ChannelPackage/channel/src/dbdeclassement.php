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

//Action to do
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
switch($do){
	//Log in User
	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 					: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 		: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 		: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 		: $natdeclassement = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 						: $libelle = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 						: $cabinet = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 				: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 				: $nbreLigne = '');

		//Data
		$_SESSION['DATA_DEC']=array(
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'natdeclassement'=> $natdeclassement,
		'libelle'=> $libelle,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		$_SESSION['GL_USER']['JOUR'] = mysqlFormat($datedeclassement);

		//Etape 2
		header('location:adddeclassement1.php?selectedTab=bds');

		break;

	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 					: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 		: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 		: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 						: $natdeclassement = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 						: $libelle = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 						: $cabinet = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 				: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 				: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  						? $statut 		= trim($_POST['statut']) 					: $statut = '0');

		$datedeclassement = mysqlFormat($datedeclassement);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		//$statut = 1;

		$numauto = myDbLastId('declass', 'ID_DECLASS', $magasin)+1;
		$codeDecl = "$numauto/$magasin";

		//Insert
		$sql  = "INSERT INTO `declass` (`CODE_DECLASS`, `CODE_MAGASIN`, `ID_EXERCICE`, `ID_DECLASS`, `REF_DECLAS`, `DCL_DATE`,
		 `DCL_LIBELLE`, `CODENATDECLASS`, `DCL_REFRAPPORT`, `DCL_CABINET`, `DCL_VALIDE`, `DCL_DATEVALID`)
		 VALUES ('".addslashes($codeDecl)."', '".addslashes($magasin)."','".addslashes($exercice)."','".addslashes($numauto)."',
		 '".addslashes($refdeclassement)."','".addslashes($datedeclassement)."', '".addslashes($libelle)."','".addslashes($natdeclassement)."',
		 '".addslashes($refrapport)."', '".addslashes($cabinet)."','$statut','".date('Y-m-d H:i:s')."');";

		//echo $sql, '<br>';
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un déclassement ($codeDecl, $refdeclassement)"); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_DEC']=array(
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'raison'=> $natdeclassement,
		'libelle'=> $libelle,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 ="";
		$sql2 ="";

		$numautoDetDecl = myDbLastId('detdeclass', 'ID_DETDECLASS', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		//Collect Data
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$numautoDetDecl++;
				$codeDetDecl = "$numautoDetDecl/$magasin";

			 	$sql1 .="INSERT INTO `detdeclass` (`CODE_DETDECLASS`, `CODE_PRODUIT`, `CODE_MAGASIN`, `CODE_DECLASS`, `ID_DETDECLASS`, `DECL_QTE`,
				`DECL_UNITE`, `DECL_REFLOT`, `DECL_DATEPEREMP`, `DECL_PA`, `DECL_MONLOT`) VALUES ('".addslashes($codeDetDecl)."',
				'".addslashes($codeproduit)."', '".addslashes($magasin)."', '".addslashes($codeDecl)."', '".addslashes($numautoDetDecl)."' ,'".addslashes($qte)."' ,
				'".addslashes($unite)."','".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."', '".addslashes($monlot)."'); ";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($codeDecl)."',
				'".addslashes($datedeclassement)."' ,'".addslashes(date('H:i:s'))."' ,	'".addslashes($qte)."' , '".addslashes($unite)."',
				'PERTE', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				 '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout des lignes de déclassement ($codeDecl, $refdeclassement)"); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout d'un mouvement($codeDecl, déclassement n°$refdeclassement)"); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_DEC']);
		//echo $sql1, '<br>', $sql2;
		header('location:declassement.php?selectedTab=bds&rs=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 	: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 	: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 	: $natdeclassement = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 			= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 			= trim($_POST['cabinet']) 			: $cabinet = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 		= trim($_POST['refrapport']) 		: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  						? $statut 			= trim($_POST['statut']) 			: $statut = '0');

		$datedeclassement = mysqlFormat($datedeclassement);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "UPDATE `declass` SET `CODE_MAGASIN`='".addslashes($magasin)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,
		`REF_DECLAS`='".addslashes($refdeclassement)."' ,	`DCL_DATE`='".addslashes($datedeclassement)."' ,
		`CODENATDECLASS`='".addslashes($natdeclassement)."' ,`DCL_LIBELLE`='".addslashes($libelle)."' ,`DCL_REFRAPPORT`= '".addslashes($refrapport)."',
		`DCL_CABINET`= '".addslashes($cabinet)."',	`DCL_VALIDE`='$statut'  WHERE CODE_DECLASS LIKE '".addslashes($xid)."';";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un déclassement ($xid, $refdeclassement)"); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_DEC']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'raison'=> $raison,
		'libelle'=> $libelle,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 ="";
		$sql2="";

		$numautoDetDecl = myDbLastId('detdeclass', 'ID_DETDECLASS', $magasin);
		$numautoMvt = myDbLastId('mouvement', 'ID_MOUVEMENT', $magasin);

		//Collect Data
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `detdeclass` SET `CODE_PRODUIT`= '".addslashes($codeproduit)."',`DECL_QTE`='".addslashes($qte)."' ,
				`DECL_UNITE`='".addslashes($unite)."',`DECL_PA`='".addslashes($prix)."' ,  `DECL_MONLOT`='".addslashes($monlot)."' ,
				`CODE_MAGASIN`='".addslashes($magasin)."'
				WHERE CODE_DECLASS LIKE '".addslashes($xid)."' AND CODE_PRODUIT='$oldcodeproduit' AND `CODE_DETDECLASS`='".addslashes($code_detdeclass)."'; ";

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,
				`CODE_MAGASIN`='".addslashes($magasin)."' ,	`MVT_DATE`='".addslashes($datedeclassement)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,
				`MVT_QUANTITE`='".addslashes($qte)."' ,	`MVT_UNITE`='".addslashes($unite)."',`MVT_VALID`='$statut', `MVT_TYPE`='S',
				`MVT_PV`='".addslashes($prix)."' , MVT_MONLOT='".addslashes($monlot)."'
				 WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_SOURCE LIKE '".addslashes($xid)."' AND MVT_NATURE='PERTE'
				 AND `MVT_TYPE`='S' ; ";
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!=''){

				$numautoDetDecl++;
				$codeDetDecl = "$numautoDetDecl/$magasin";

				$sql1 .="INSERT INTO `detdeclass` (`CODE_DETDECLASS`, `CODE_PRODUIT`, `CODE_DECLASS`, `ID_DETDECLASS`, `DECL_QTE`,
				`DECL_UNITE`, `DECL_REFLOT`, `DECL_DATEPEREMP`, `DECL_PA`, `DECL_MONLOT`, `CODE_MAGASIN`) VALUES ('".addslashes($codeDetDecl)."',
				'".addslashes($codeproduit)."', '".addslashes($xid)."', '".addslashes($numautoDetDecl)."' ,'".addslashes($qte)."' ,
				'".addslashes($unite)."','".addslashes($reflot)."' ,'".addslashes(mysqlFormat($dateperemp))."', '".addslashes($prix)."',
				'".addslashes($monlot)."', '".addslashes($magasin)."'); ";

				$numautoMvt++;
				$codeMvt = "$numautoMvt/$magasin";

				$sql2 .="INSERT INTO `mouvement` (`CODE_MOUVEMENT`, `ID_EXERCICE`, `CODE_PRODUIT`, `CODE_MAGASIN`, `ID_MOUVEMENT`, `ID_SOURCE`,
				`MVT_DATE`, `MVT_TIME`, `MVT_QUANTITE`, `MVT_UNITE`, `MVT_NATURE`, `MVT_VALID`, `MVT_DATEVALID`, `MVT_TYPE`, `MVT_REFLOT`,
				`MVT_DATEPEREMP`,  `MVT_PV`,  `MVT_MONLOT`) VALUES ('".addslashes($codeMvt)."',  '".addslashes($exercice)."',
				'".addslashes($codeproduit)."',	'".addslashes($magasin)."',	'".addslashes($numautoMvt)."', '".addslashes($xid)."',
				'".addslashes($datedeclassement)."' ,'".addslashes(date('H:i:s'))."' ,	'".addslashes($qte)."' , '".addslashes($unite)."',
				'PERTE', '$statut', '".date('Y-m-d H:i:s')."','S','".addslashes($reflot)."','".addslashes(mysqlFormat($dateperemp))."',
				 '".addslashes($prix)."', '".addslashes($monlot)."') ; ";
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification des lignes de déclassement ($xid, $refdeclassement)"); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement($xid, déclassement n°$refdeclassement)"); //updateLog($username, $idcust, $action='' )
		}
		//unset($_SESSION['DATA_DEC']);
		header('location:declassement.php?selectedTab=bds&rs=2');
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
		$sql  = "UPDATE `declass` SET `DCL_VALIDE`='2', DCL_DATEVALID='".addslashes(date('Y-m-d'))."'  WHERE CODE_DECLASS LIKE '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2, MVT_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."'  WHERE (MVT_NATURE LIKE 'PERTE')
		AND ID_SOURCE LIKE '".addslashes($xid)."';";

		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Annulation d\'un declassement ($xid, $oldcode)"); //updateLog($username, $idcust, $action='' )
		header('location:declassement.php?selectedTab=bds&rst=1');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 	: $refdeclassement = '');

		//Insert
		$sql  = "UPDATE `declass` SET `DCL_VALIDE`='1', `DCL_DATEVALID`='".date('Y-m-d H:i:s')."' WHERE CODE_DECLASS LIKE '".addslashes($xid)."';";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un déclassement ($xid, $refdeclassement)"); //updateLog($username, $idcust, $action='' )

		//Data
		$sql1 ="";
		//Collect Data
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 			? $qte 				= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `mouvement` SET `MVT_VALID`='1', `MVT_TYPE`='S',`MVT_DATEVALID`='".date('Y-m-d H:i:s')."'
				 WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_SOURCE LIKE '".addslashes($xid)."' AND MVT_NATURE='PERTE';";
			}
		}
		if (($sql1 !='')) {
			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement($xid, déclassement n°$refdeclassement)"); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_DEC']);
		header('location:declassement.php?selectedTab=bds&rs=3');
		break;

	case 'detail':
		(isset($_GET['xid']) ? $xid = $_GET['xid'] : $xid ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//PERTE
		$sql = "SELECT * FROM  `declass` INNER JOIN natdeclass ON (natdeclass.CODENATDECLASS LIKE declass.CODENATDECLASS)
		WHERE CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_DECLASS` LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_DEC']=array(
		'xid'=>$xid,
		'exercice'=>$row['ID_EXERCICE'],
		'datedeclassement'=>frFormat2($row['DCL_DATE']),
		'refdeclassement'=>$row['REF_DECLAS'],
		'natdeclassement'=>$row['CODENATDECLASS'],
		'raison'=>$row['LIBNATDECLASS'],
		'cabinet'=>$row['DCL_CABINET'],
		'refrapport'=>$row['DCL_REFRAPPORT'],
		'libelle'=>stripslashes($row['DCL_LIBELLE']),
		'datevalid'=>frFormat($row['DCL_DATEVALID']),
		'statut'=>$row['DCL_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES PERTE
		$sql = "SELECT detdeclass.*, produit.CODE_PRODUIT, produit.PRD_LIBELLE FROM `detdeclass`
		INNER JOIN produit ON (produit.CODE_PRODUIT LIKE detdeclass.CODE_PRODUIT) WHERE CODE_DECLASS LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_DEC']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$row['CODE_DETDECLASS'],'monlot'=>$row['DECL_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$row['DECL_QTE'], 'unite'=>$row['DECL_UNITE'], 'prix'=>$row['DECL_PA'], 'reflot'=>$row['DECL_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['DECL_DATEPEREMP']))));
		}
		$_SESSION['DATA_DEC']['nbreLigne'] = $query->rowCount();
		header('location:detaildeclassement.php?selectedTab=bds&rst=1');
		break;

	case 'journal':
		(isset($_GET['xid']) ? $xid = $_GET['xid'] : $xid ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//PERTE
		$sql = "SELECT * FROM  `declass` INNER JOIN natdeclass ON (natdeclass.CODENATDECLASS LIKE declass.CODENATDECLASS)
		WHERE CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_DECLASS` LIKE '".addslashes($xid)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_DEC']=array(
		'xid'=>$row['CODE_DECLASS'],
		'exercice'=>$row['ID_EXERCICE'],
		'datedeclassement'=>frFormat2($row['DCL_DATE']),
		'refdeclassement'=>$row['REF_DECLAS'],
		'natdeclassement'=>$row['CODENATDECLASS'],
		'raison'=>$row['LIBNATDECLASS'],
		'libelle'=>$row['DCL_LIBELLE'],
		'cabinet'=>$row['DCL_CABINET'],
		'refrapport'=>$row['DCL_REFRAPPORT'],
		'datevalid'=>frFormat($row['DCL_DATEVALID']),
		'statut'=>$row['DCL_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES PERTE
		$sql = "SELECT detdeclass.*, produit.CODE_PRODUIT, produit.PRD_LIBELLE FROM `detdeclass`
		INNER JOIN produit ON (produit.CODE_PRODUIT LIKE detdeclass.CODE_PRODUIT) WHERE CODE_DECLASS LIKE '".addslashes($xid)."' ORDER BY detdeclass.CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_DEC']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$row['CODE_DETDECLASS'],'monlot'=>$row['DECL_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['DECL_QTE'], 'unite'=>$row['DECL_UNITE'], 'prix'=>$row['DECL_PA'], 'reflot'=>$row['DECL_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['DECL_DATEPEREMP']))));
		}
		$_SESSION['DATA_DEC']['nbreLigne'] = $query->rowCount();

		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` WHERE MVT_NATURE LIKE 'PERTE' AND ID_SOURCE LIKE '".addslashes($xid)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_DEC']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_DEC']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>'', 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID'], 'prix'=>$row['MVT_PV']));
		}

		$_SESSION['DATA_DEC']['nbreLigne2'] = $query->rowCount();

		header('location:journaldeclassement.php?selectedTab=bds&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) : $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_DECLAS) AS NBRE FROM  `declass` WHERE `CODE_DECLAS` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code d&eacute;classement.';}
		}
		echo $msg;
		break;

	case 'sendDate':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$_SESSION['GL_USER']['JOUR']= mysqlFormat($code);
		}
		echo 1;
		break;
	default : ///Nothing
}
}//Fin if

elseif($myaction !='')
//myaction
switch($myaction){
	case 'addline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 		: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 		: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 						: $natdeclassement = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 						: $libelle = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 						: $cabinet = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 				: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['xid']) && $_POST['xid']!=''  							? $xid 		= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_DEC']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'natdeclassement'=> $natdeclassement,
		'libelle'=> $libelle,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_DEC']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_DEC']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$code_detdeclass,'monlot'=>$monlot,'codeproduit'=>$codeproduit,'oldcodeproduit'=>$oldcodeproduit,  'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
		}
		//Add line
		$_SESSION['DATA_DEC']['nbreLigne'] +=1;
		$_SESSION['DATA_DEC']['nbreLigne'] -=$moins;
		header('location:adddeclassement1.php?selectedTab=bds');
		break;

	case 'addline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 		: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 		: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 		: $natdeclassement = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 						: $cabinet = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 						: $libelle = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 				: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 		= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_DEC']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'natdeclassement'=> $natdeclassement,
		'libelle'=> $libelle,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_DEC']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_DEC']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$code_detdeclass,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
		}
		//Add line
		$_SESSION['DATA_DEC']['nbreLigne'] +=1;
		$_SESSION['DATA_DEC']['nbreLigne'] -=$moins;
		header('location:editdeclassement.php?selectedTab=bds');
		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 	: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 	: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 	: $natdeclassement = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 					: $cabinet = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 					: $libelle = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 			: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  			? $rowSelection 	= trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_DEC']=array(
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'natdeclassement'=> $natdeclassement,
		'libelle'=> $libelle,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$code_detdeclass,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit,'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_DEC']['nbreLigne'] -=$supp;
		header('location:adddeclassement1.php?selectedTab=bds');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datedeclassement']) && $_POST['datedeclassement']!=''  	? $datedeclassement = trim($_POST['datedeclassement']) 		: $datedeclassement = '');
		(isset($_POST['refdeclassement']) && $_POST['refdeclassement']!=''  	? $refdeclassement = trim($_POST['refdeclassement']) 		: $refdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!=''  	? $natdeclassement 	= trim($_POST['natdeclassement']) 	: $natdeclassement = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 						: $libelle = '');
		(isset($_POST['cabinet']) && $_POST['cabinet']!=''  					? $cabinet 	= trim($_POST['cabinet']) 						: $cabinet = '');
		(isset($_POST['refrapport']) && $_POST['refrapport']!=''  				? $refrapport 	= trim($_POST['refrapport']) 				: $refrapport = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_DEC']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'refdeclassement'=>$refdeclassement,
		'natdeclassement'=> $natdeclassement,
		'libelle'=> $libelle,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_DEC']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_DEC']['nbreLigne']; $i++){
			(isset($_POST['code_detdeclass'.$i]) && $_POST['code_detdeclass'.$i] 	? $code_detdeclass 	= $_POST['code_detdeclass'.$i] 	: $code_detdeclass 	= '');
			(isset($_POST['monlot'.$i]) && $_POST['monlot'.$i] 					? $monlot 			= $_POST['monlot'.$i] 			: $monlot 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) && $_POST['unite'.$i]				? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['prix'.$i]) && $_POST['prix'.$i]					? $prix 		= $_POST['prix'.$i] 		: $prix 		= '');
			(isset($_POST['reflot'.$i]) && $_POST['reflot'.$i]				? $reflot		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperemp'.$i]) && $_POST['dateperemp'.$i]		? $dateperemp 	= $_POST['dateperemp'.$i] 	: $dateperemp 	= '');

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$code_detdeclass,'monlot'=>$monlot,'codeproduit'=>$codeproduit, 'oldcodeproduit'=>$oldcodeproduit,'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite, 'prix'=>$prix, 'reflot'=>$reflot, 'dateperemp'=>$dateperemp));
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
				$sql = "DELETE FROM  `detdeclass` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `CODE_DECLASS` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT LIKE '".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'PERTE' AND `ID_SOURCE` LIKE '".addslashes($xid)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_DEC']['nbreLigne'] -=$supp;
		header('location:editdeclassement.php?selectedTab=bds');
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
		//PERTE
		$sql = "SELECT * FROM  `declass` WHERE CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_DECLASS` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_DEC']=array(
		'xid'=>$row['CODE_DECLASS'],
		'exercice'=>$row['ID_EXERCICE'],
		'datedeclassement'=>frFormat2($row['DCL_DATE']),
		'refdeclassement'=>$row['REF_DECLAS'],
		'natdeclassement'=>$row['CODENATDECLASS'],
		'libelle'=>$row['DCL_LIBELLE'],
		'cabinet'=>$row['DCL_CABINET'],
		'refrapport'=>$row['DCL_REFRAPPORT'],
		'datevalid'=>frFormat($row['DCL_DATEVALID']),
		'statut'=>$row['DCL_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES PERTE
		$sql = "SELECT detdeclass.*, produit.CODE_PRODUIT, produit.PRD_LIBELLE FROM `detdeclass`
		INNER JOIN produit ON (produit.CODE_PRODUIT LIKE detdeclass.CODE_PRODUIT) WHERE CODE_DECLASS LIKE '".addslashes($split[0])."'
		ORDER BY ID_DETDECLASS ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_DEC']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$row['CODE_DETDECLASS'],'monlot'=>$row['DECL_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'oldcodeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['DECL_QTE'], 'unite'=>$row['DECL_UNITE'], 'prix'=>$row['DECL_PA'], 'reflot'=>$row['DECL_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['DECL_DATEPEREMP']))));
		}
		$_SESSION['DATA_DEC']['nbreLigne'] = $query->rowCount();
		header('location:editdeclassement.php?selectedTab=bds&rs=2');
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
		//PERTE
		$sql = "SELECT * FROM  `declass` INNER JOIN natdeclass ON (natdeclass.CODENATDECLASS LIKE declass.CODENATDECLASS)
		WHERE CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND  `CODE_DECLASS` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_DEC']=array(
		'xid'=>$row['CODE_DECLASS'],
		'exercice'=>$row['ID_EXERCICE'],
		'datedeclassement'=>frFormat2($row['DCL_DATE']),
		'refdeclassement'=>$row['REF_DECLAS'],
		'natdeclassement'=>$row['CODENATDECLASS'],
		'raison'=>$row['LIBNATDECLASS'],
		'libelle'=>$row['DCL_LIBELLE'],
		'cabinet'=>$row['DCL_CABINET'],
		'refrapport'=>$row['DCL_REFRAPPORT'],
		'datevalid'=>frFormat($row['DCL_DATEVALID']),
		'statut'=>$row['DCL_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES PERTE
		$sql = "SELECT detdeclass.*, produit.CODE_PRODUIT, produit.PRD_LIBELLE FROM `detdeclass`
		INNER JOIN produit ON (produit.CODE_PRODUIT LIKE detdeclass.CODE_PRODUIT) WHERE CODE_DECLASS LIKE '".addslashes($split[0])."'
		ORDER BY ID_DETDECLASS ASC;";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_DEC']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_DEC']['ligne'], array('code_detdeclass'=>$row['CODE_DETDECLASS'],'monlot'=>$row['DECL_MONLOT'],'codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['DECL_QTE'], 'unite'=>$row['DECL_UNITE'], 'prix'=>$row['DECL_PA'], 'reflot'=>$row['DECL_REFLOT'],'dateperemp'=>preg_replace('[-]','/',frFormat2($row['DECL_DATEPEREMP']))));
		}
		$_SESSION['DATA_DEC']['nbreLigne'] = $query->rowCount();
		header('location:validdeclassement.php?selectedTab=bds&rs=3');
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
		$sql = "UPDATE `declass` SET  DCL_VALIDE=2, DCL_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."' WHERE `CODE_DECLASS` LIKE '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2, MVT_DATEVALID='".addslashes(date('Y-m-d H:i:s'))."'  WHERE (MVT_NATURE LIKE 'PERTE') AND ID_SOURCE LIKE '".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'un declassement ('.$xid.', '.$oldcode.')'); //updateLog($username, $idcust, $action='' )
		//echo $sql;
		header('location:declassement.php?selectedTab=bds&rst=1');
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
			$sql = "DELETE FROM  `detdeclass` WHERE `CODE_DECLASS` LIKE '".addslashes($split[0])."';
			DELETE FROM  `declass` WHERE `CODE_DECLASS` LIKE '".addslashes($split[0])."';";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` LIKE '".addslashes($split[0])."' AND MVT_NATURE LIKE 'PERTE'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:declassement.php?selectedTab=bds&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');
}

elseif($myaction =='' && $do ='') header('location:../index.php');

?>
