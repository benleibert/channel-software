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
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 		= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  				? $datesortie 		= trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  				? $dateentree 		= trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  ? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 			= trim($_POST['raison']) 			: $raison = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 		= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 			= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne = '');

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'statut'=> '',
		'controleur'=>$controleur,
		'libelle'=>$libelle,
		'raison'=> $raison,
		'nbreLigne'=>$nbreLigne
		);
		//print_r($_SESSION['DATA']);
		//Etape 2
		header('location:addreconditionnement1.php?selectedTab=bds');
		break;

	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  				? $datesortie = trim($_POST['datesortie']) 			: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  				? $dateentree = trim($_POST['dateentree']) 			: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  			? $libelleetat 	= trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  						? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$datesortie = mysqlFormat($datesortie);
		$dateentree = mysqlFormat($dateentree);
		$magasin= $_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "INSERT INTO `recondit` (`ID_EXERCICE` ,`REC_RAISON` ,`CODE_MAGASIN`,`REC_DATESORTIE`, `REC_DATERETOUR`, `REC_CONTROLEUR` ,
		`REC_VALIDE` ,`CODE_RECOND`,`REC_LIBELLE`)	VALUES ( '".addslashes($exercice)."', '".addslashes($raison)."',
		'".addslashes($magasin)."','".addslashes($datesortie)."' , '".addslashes($dateentree)."' , '".addslashes($controleur)."' ,
		'$statut' , '".addslashes($codereconditionnement)."' ,'".addslashes($libelle)."' );";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$insert_id = $cnx->lastInsertId();
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un reconditionnement ('.$insert_id.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'controleur'=>$controleur,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 ="";
		$sql2 ="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['qtelivr'.$i]) 	? $qtelivr		= $_POST['qtelivr'.$i] 		: $qtelivr 	= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 		= $_POST['typeemballage'.$i] 		: $typeemballage 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 	= $_POST['unite'.$i] 		: $colissage 	= '');
			(isset($_POST['cause'.$i]) 		? $cause 	= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 	= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="INSERT INTO `prd_recond` (`CODE_PRODUIT` ,`ID_RECONDIT` ,`PRDREC_CAUSE` ,`PRDREC_UNITES` ,`PRDREC_QTES`,
				`PRDREC_UNITEE`,`PRDREC_QTEE`, `PRDREC_TYPEEMB`, `PRDREC_COLISSAGE`) VALUES ( '".addslashes($codeproduit)."',
				'".addslashes($insert_id)."', '".addslashes($cause)."' , '".addslashes($unite)."','".addslashes($qte)."' ,
				'".addslashes($unite)."', '".addslashes($qtelivr)."' ,'".addslashes($typeemballage)."' ,'".addslashes($colissage)."'); ";

				$sql2 .="INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_TIME`,
				`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`, `MVT_TYPE`) VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."',
				'".addslashes($magasin)."', '".addslashes($insert_id)."', '".addslashes($datesortie)."' ,'".addslashes(date('H:i:s'))."',
				'".addslashes($qte)."' , 'RECONDITIONNEMENT SORTANT','".addslashes($unite)."', '$statut','S') ;
				INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_TIME`,`MVT_QUANTITE` ,
				`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`, `MVT_TYPE`) VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."',
				'".addslashes($magasin)."', '".addslashes($insert_id)."', '".addslashes($dateentree)."' ,'".addslashes(date('H:i:s'))."',
				'".addslashes($qtelivr)."' , 'RECONDITIONNEMENT ENTRANT','".addslashes($unite)."', '$statut','E') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de reconditionnement ('.$insert_id.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un mouvement('.$insert_id.', reconditionnement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		//echo $sql, $sql1, $sql2;
		header('location:reconditionnement.php?selectedTab=bds&rst=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  	? $dateentree = trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  				? $libelleetat 	= trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$datesortie = mysqlFormat($datesortie);
		$dateentree = mysqlFormat($dateentree);
		$magasin= $_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "UPDATE `recondit` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REC_RAISON`='".addslashes($raison)."' ,`REC_DATESORTIE`='".addslashes($datesortie)."', `REC_DATERETOUR`='".addslashes($dateentree)."' ,";
	 	$sql .= "`REC_CONTROLEUR`='".addslashes($controleur)."', `CODE_MAGASIN`='".addslashes($magasin)."',`REC_VALIDE`='$statut' ,`CODE_RECOND`='".addslashes($codereconditionnement)."',`REC_LIBELLE`='".addslashes($libelleetat)."'  WHERE ID_RECONDIT='$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$insert_id = $cnx->lastInsertId();
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'controleur'=>$controleur,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 =""; $sql2 ="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 		= $_POST['typeemballage'.$i] 		: $typeemballage 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 	= $_POST['unite'.$i] 		: $colissage 	= '');
			(isset($_POST['qtelivr'.$i]) 	? $qtelivr		= $_POST['qtelivr'.$i] 		: $qtelivr 	= '');
			(isset($_POST['cause'.$i]) 		? $cause 	= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 	= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `prd_recond` SET `CODE_PRODUIT`='".addslashes($codeproduit)."',`PRDREC_CAUSE`='".addslashes($cause)."' ,`ID_RECONDIT`='".addslashes($xid)."' ,`PRDREC_QTES`='".addslashes($qte)."' ,
				`PRDREC_UNITES`='".addslashes($unite)."' ,`PRDREC_QTEE`='".addslashes($qtelivr)."' ,`PRDREC_UNITEE`='".addslashes($unite)."',
			 	`PRDREC_TYPEEMB`='".addslashes($typeemballage)."', `PRDREC_COLISSAGE`='".addslashes($colissage)."' WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `ID_RECONDIT`='".addslashes($xid)."'; ";

				$sql2 .="UPDATE  `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`ID_SOURCE`='".addslashes($xid)."' ,
				`MVT_DATE`='".addslashes($datesortie)."'  ,`MVT_QUANTITE`='".addslashes($qte)."' ,`MVT_NATURE`='RECONDITIONNEMENT SORTANT',`MVT_UNITE`='".addslashes($unite)."', `MVT_VALID`='$statut', `MVT_TYPE`='S'
				WHERE CODE_PRODUIT='".addslashes($oldcodeproduit)."' AND `MVT_NATURE` LIKE 'RECONDITIONNEMENT SORTANT' AND ID_SOURCE='$xid';
				UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`ID_SOURCE`='".addslashes($xid)."' ,
				`MVT_DATE`='".addslashes($dateentree)."' ,`MVT_QUANTITE`='".addslashes($qtelivr)."' ,`MVT_NATURE`='RECONDITIONNEMENT ENTRANT',`MVT_UNITE`='".addslashes($unite)."', `MVT_VALID`='$statut' , `MVT_TYPE`='E'
				WHERE CODE_PRODUIT='".addslashes($oldcodeproduit)."' AND `MVT_NATURE` LIKE 'RECONDITIONNEMENT ENTRANT' AND ID_SOURCE='$xid'; ";
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!='' && $oldcodeproduit!=$codeproduit){
				$sql1 .="INSERT INTO `prd_recond` (`CODE_PRODUIT` ,`ID_RECONDIT`, `PRDREC_CAUSE` ,`PRDREC_QTES` ,`PRDREC_UNITES` ,`PRDREC_QTEE` , `PRDREC_TYPEEMB`, `PRDREC_COLISSAGE`, `PRDREC_UNITEE`)
				VALUES ( '".addslashes($codeproduit)."', '".addslashes($xid)."', '".addslashes($cause)."' ,'".addslashes($qte)."' , '".addslashes($unite)."', '".addslashes($qtelivr)."' , '".addslashes($typeemballage)."', '".addslashes($colissage)."', '".addslashes($unite)."'); ";

				$sql2 .="INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE`,`MVT_TIME` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`, `MVT_TYPE`='S')
				VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($datesortie)."'  ,'".addslashes(date('H:i:s'))."', '".addslashes($qte)."' , 'RECONDITIONNEMENT SORTANT','".addslashes($unite)."', '$statut') ;
				INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE`,`MVT_TIME` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`, `MVT_TYPE`='E')
				VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($dateentree)."'  ,'".addslashes(date('H:i:s'))."', '".addslashes($qtelivr)."' , 'RECONDITIONNEMENT ENTRANT','".addslashes($unite)."', '$statut') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un mouvement('.$xid.', reconditionnement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		//echo $sql, $sql1, $sql2;
		header('location:reconditionnement.php?selectedTab=bds&rst=1');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  	? $dateentree = trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['libelleetat']) && $_POST['libelleetat']!=''  				? $libelleetat 	= trim($_POST['libelleetat']) 		: $libelleetat = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$datesortie = mysqlFormat($datesortie);
		$dateentree = mysqlFormat($dateentree);
		$magasin= $_SESSION['GL_USER']['MAGASIN'];

		//Insert
		$sql  = "UPDATE `recondit` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REC_RAISON`='".addslashes($raison)."' ,`REC_DATESORTIE`='".addslashes($datesortie)."', `REC_DATERETOUR`='".addslashes($dateentree)."' ,";
		$sql .= "`REC_VALIDE`='$statut' , `CODE_MAGASIN`='".addslashes($magasin)."', `CODE_RECOND`='".addslashes($codereconditionnement)."',`REC_LIBELLE`='".addslashes($libelleetat)."'  WHERE ID_RECONDIT='$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$insert_id = $cnx->lastInsertId();
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation d\'un reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'controleur'=>$controleur,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 =""; $sql2 ="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['qtelivr'.$i]) 	? $qtelivr		= $_POST['qtelivr'.$i] 		: $qtelivr 	= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 		= $_POST['typeemballage'.$i] 		: $typeemballage 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 	= $_POST['unite'.$i] 		: $colissage 	= '');
			(isset($_POST['cause'.$i]) 		? $cause 	= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['cause'.$i]) 		? $cause 	= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 	= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `prd_recond` SET `CODE_PRODUIT`='".addslashes($codeproduit)."',`PRDREC_CAUSE`='".addslashes($cause)."'  ,`ID_RECONDIT`='".addslashes($xid)."' ,`PRDREC_QTES`='".addslashes($qte)."' ,
				`PRDREC_UNITES`='".addslashes($unite)."' ,`PRDREC_QTEE`='".addslashes($qtelivr)."' ,`PRDREC_UNITEE`='".addslashes($unite)."',
				`PRDREC_TYPEEMB`='".addslashes($typeemballage)."', `PRDREC_COLISSAGE`='".addslashes($colissage)."' WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `ID_RECONDIT`='".addslashes($xid)."';";

				$sql2 .="UPDATE  `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`ID_SOURCE`='".addslashes($xid)."' ,
				`MVT_DATE`='".addslashes($datesortie)."'  ,`MVT_QUANTITE`='".addslashes($qte)."' ,`MVT_NATURE`='RECONDITIONNEMENT SORTANT',`MVT_UNITE`='".addslashes($unite)."', `MVT_VALID`='$statut'
				WHERE CODE_PRODUIT='".addslashes($oldcodeproduit)."' AND `MVT_NATURE` LIKE 'RECONDITIONNEMENT SORTANT' AND ID_SOURCE='$xid';
				UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`ID_SOURCE`='".addslashes($xid)."' ,
				`MVT_DATE`='".addslashes($dateentree)."' ,`MVT_QUANTITE`='".addslashes($qtelivr)."' ,`MVT_NATURE`='RECONDITIONNEMENT ENTRANT',`MVT_UNITE`='".addslashes($unite)."', `MVT_VALID`='$statut'
				WHERE CODE_PRODUIT='".addslashes($oldcodeproduit)."' AND `MVT_NATURE` LIKE 'RECONDITIONNEMENT ENTRANT' AND ID_SOURCE='$xid'; ";
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!=''){
				$sql1 .="INSERT INTO `prd_recond` (`CODE_PRODUIT` ,`ID_RECONDIT` ,`PRDREC_CAUSE` ,`PRDREC_QTES` ,`PRDREC_UNITES` ,`PRDREC_QTEE` ,`PRDREC_UNITEE`) ";
				$sql1 .="VALUES ( '".addslashes($codeproduit)."', '".addslashes($xid)."','".addslashes($cause)."' , '".addslashes($qte)."' , '".addslashes($unite)."', '".addslashes($qtelivr)."' , '".addslashes($unite)."'); ";

				$sql2 .="INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE`,`MVT_TIME` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`) ";
				$sql2 .="VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($datesortie)."'  ,'".addslashes(date('H:i:s'))."',
				'".addslashes($qte)."' , 'RECONDITIONNEMENT SORTANT','".addslashes($unite)."', '$statut') ; ";
				$sql2 .="INSERT INTO `mouvement` (`CODE_PRODUIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_TIME`,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`) ";
				$sql2 .="VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($dateentree)."'  ,'".addslashes(date('H:i:s'))."', '".addslashes($qtelivr)."' , 'RECONDITIONNEMENT ENTRANT','".addslashes($unite)."', '$statut') ; ";
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation des lignes de reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation d\'un mouvement('.$xid.', reconditionnement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		//echo $sql, $sql1, $sql2;
		header('location:reconditionnement.php?selectedTab=bds&rst=1');
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
		//RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM  `recondit` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `ID_RECONDIT` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'dateentree'=>frFormat2($row['REC_DATERETOUR']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'controleur'=>$row['REC_CONTROLEUR'],
		'libelleetat'=>$row['REC_LIBELLE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM `prd_recond` INNER JOIN produit ON (prd_recond.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_RECONDIT = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'cause'=>$row['PRDREC_CAUSE'],'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$row['PRDREC_QTES'], 'qtelivr'=>$row['PRDREC_QTEE'],'typeemballage'=>$row['PRDREC_TYPEEMB'],'colissage'=>$row['PRDREC_COLISSAGE'], 'unite'=>$row['PRDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:detailreconditionnement.php?selectedTab=bds&rst=1');
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
		//RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM  `recondit` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `ID_RECONDIT` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);


		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'dateentree'=>frFormat2($row['REC_DATERETOUR']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'controleur'=>$row['REC_CONTROLEUR'],
		'libelleetat'=>$row['REC_LIBELLE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM `prd_recond` INNER JOIN produit ON (prd_recond.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_RECONDIT = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'cause'=>$row['PRDREC_CAUSE'],'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$row['PRDREC_QTES'], 'qtelivr'=>$row['PRDREC_QTEE'],'typeemballage'=>$row['PRDREC_TYPEEMB'],'colissage'=>$row['PRDREC_COLISSAGE'], 'unite'=>$row['PRDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();


		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` WHERE MVT_NATURE LIKE 'RECONDITIONNEMENT%' AND ID_SOURCE = '".addslashes($id)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID']));
		}

		$_SESSION['DATA_RECD']['nbreLigne2'] = $query->rowCount();

		//print_r($_SESSION['DATA_RECD']['journal']);
		header('location:journalreconditionnement.php?selectedTab=bds&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' ? $code = trim($_POST['code']) : $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_RECOND) AS NBRE FROM  `recondit` WHERE `CODE_RECOND` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code reconditionnement.';}
		}
		echo $msg;
		break;

	default : ///Nothing
}
}//Fin if

elseif($myaction !='')
//myaction
switch($myaction){
	case 'addline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  				? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  				? $dateentree = trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 			: $raison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 	: $controleur = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  						? $statut 		= trim($_POST['statut']) 		: $statut = '0');
		$magasin='MAG0';

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'libelle'=> $libelle,
		'nbreLigne'=>$nbreLigne,
		'controleur'=>$controleur,
		'ligne'=>array()
		);

		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 		: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 			: $produit 		= '');
			(isset($_POST['qte'.$i]) 			? $qte 				= $_POST['qte'.$i] 				: $qte 			= '');
			(isset($_POST['qtelivr'.$i]) 		? $qtelivr			= $_POST['qtelivr'.$i] 			: $qtelivr 		= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 	= $_POST['typeemballage'.$i] 	: $typeemballage= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 		= $_POST['colissage'.$i] 		: $colissage 	= '');
			(isset($_POST['cause'.$i]) 			? $cause 			= $_POST['cause'.$i] 			: $cause 		= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 			: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit,'cause'=>$cause, 'produit'=>$produit, 'qte'=>$qte, 'qtelivr'=>$qtelivr,'typeemballage'=>$typeemballage,'colissage'=>$colissage, 'unite'=>$unite));

		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA']);
		header('location:addreconditionnement1.php?selectedTab=bds');
		break;

	case 'addline1':
		(isset($_POST['xid']) && $_POST['xid']!=''					? $xid 		= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''		? $exercice = trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  	? $dateentree = trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  			? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  	? $nbreLigne = trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  			? $statut 	= trim($_POST['statut']) 			: $statut = '0');
		$magasin='MAG0';

		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'libelle'=> $libelle,
		'controleur'=>$controleur,
		'nbreLigne'=>$nbreLigne,
		'libelleetat'=>$libelleetat,
		'statut'=>$statut,
		'ligne'=>array()
		);

		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])	? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 			? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['qtelivr'.$i]) 		? $qtelivr		= $_POST['qtelivr'.$i] 		: $qtelivr 	= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage= $_POST['typeemballage'.$i]: $typeemballage2 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 	= $_POST['colissage'.$i] 	: $colissage 	= '');
			(isset($_POST['cause'.$i]) 			? $cause 		= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 			? $unite 		= $_POST['unite'.$i] 		: $unite 	= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit,'cause'=>$cause, 'produit'=>$produit, 'qte'=>$qte, 'qtelivr'=>$qtelivr,'typeemballage'=>$typeemballage,'colissage'=>$colissage,'unite'=>$unite));

		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA']);
		header('location:editreconditionnement.php?selectedTab=bds');
		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''	? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!='' ? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!='' ? $dateentree = trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  		? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  ? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  		? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$magasin='MAG0';

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'libelle'=> $libelle,
		'controleur'=>$controleur,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])	? $codeproduit 		= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 		? $produit 			= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 			? $qte 				= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['qtelivr'.$i]) 		? $qtelivr 			= $_POST['qtelivr'.$i] 		: $qtelivr 	= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 	= $_POST['typeemballage'.$i]: $typeemballage 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 		= $_POST['colissage'.$i] 	: $colissage 	= '');
			(isset($_POST['cause'.$i]) 			? $cause 			= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 			? $unite 			= $_POST['unite'.$i] 		: $unite 	= '');
						//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit,'cause'=>$cause, 'produit'=>$produit,  'qte'=>$qte, 'qtelivr'=>$qtelivr,'typeemballage'=>$typeemballage,'colissage'=>$colissage, 'unite'=>$unite));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] -=$supp;
		header('location:addreconditionnement1.php?selectedTab=bds');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codedeclassement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!=''  					? $libelle 	= trim($_POST['libelle']) 			: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['controleur']) && $_POST['controleur']!=''  				? $controleur 	= trim($_POST['controleur']) 		: $controleur = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'libelle'=> $libelle,
		'controleur'=>$controleur,
		'libelleetat'=>$libelleetat,
		'nbreLigne'=>$nbreLigne,
		'statut'=>$statut,
		'ligne'=>array()
		);

		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['typeemballage'.$i]) 	? $typeemballage 		= $_POST['typeemballage'.$i] 		: $typeemballage 	= '');
			(isset($_POST['colissage'.$i]) 		? $colissage 	= $_POST['unite'.$i] 		: $colissage 	= '');
			(isset($_POST['cause'.$i]) 		? $cause 	= $_POST['cause'.$i] 		: $cause 	= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit,'cause'=>$cause, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite));
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
				$sql = "DELETE FROM  `prd_recond` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `ID_RECONDIT` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'RECONDITIONNEMENT SORTANT' AND `ID_SOURCE` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'RECONDITIONNEMENT ENTRANT' AND `ID_SOURCE` = '".addslashes($xid)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL =>
			}
		}
		$_SESSION['DATA_RECD']['nbreLigne'] -=$supp;
		// $sql;
		header('location:editreconditionnements.php?selectedTab=bds');
		break;

	case 'edit':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/ /',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//DECLASSEMENT
		$sql = "SELECT * FROM  `recondit` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `ID_RECONDIT` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'dateentree'=>frFormat2($row['REC_DATERETOUR']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'controleur'=>$row['REC_CONTROLEUR'],
		'libelleetat'=>$row['REC_LIBELLE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT
		$sql = "SELECT * FROM `prd_recond`  INNER JOIN produit ON (prd_recond.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],'cause'=>$row['PRDREC_CAUSE'], 'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$row['PRDREC_QTES'], 'qtelivr'=>$row['PRDREC_QTEE'],'typeemballage'=>$row['PRDREC_TYPEEMB'],'colissage'=>$row['PRDREC_COLISSAGE'], 'unite'=>$row['PRDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:editreconditionnement.php?selectedTab=bds&rst=1');
		break;

	case 'recond':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/ /',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//DECLASSEMENT
		$sql = "SELECT * FROM  `recondit` WHERE `ID_RECONDIT` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'controleur'=>$row['REC_CONTROLEUR'],
		'libelleetat'=>$row['REC_LIBELLE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT
		$sql = "SELECT * FROM `prd_recond`  INNER JOIN produit ON (prd_recond.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$i=0;
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$rest =  $row['PRDREC_QTES'] - entreePourProduitRec($split[0], $row['CODE_PRODUIT']);
			if($rest !=0) {
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'],'cause'=>$row['PRDREC_CAUSE'], 'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$rest, 'qtelivr'=>'', 'typeemballage'=>$row['PRDREC_TYPEEMB'],'colissage'=>$row['PRDREC_COLISSAGE'], 'unite'=>$row['PRDREC_UNITES']));
				$i++;
			}
		}
		$_SESSION['DATA_RECD']['nbreLigne'] =$i;
		if($_SESSION['DATA_RECD']['nbreLigne']==0){header('location:recondreconditionnement1.php?selectedTab=bds');	}
		else{
			//Etape 2
			header('location:recondreconditionnement.php?selectedTab=bds&rst=1');
		}
		break;

	case 'validate':
		(isset($_POST['rowSelection']) ? $id = $_POST['rowSelection'][0] : $id ='');
		$split = preg_split('/ /',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//DECLASSEMENT
		$sql = "SELECT * FROM  `recondit` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND  `ID_RECONDIT` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'dateentree'=>frFormat2($row['REC_DATERETOUR']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'controleur'=>$row['REC_CONTROLEUR'],
		'libelle'=>$row['REC_LIBELLE'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT
		$sql = "SELECT * FROM `prd_recond` INNER JOIN produit ON (prd_recond.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'cause'=>$row['PRDREC_CAUSE'],'produit'=>stripslashes($row['PRD_LIBELLE']),  'qte'=>$row['PRDREC_QTES'], 'qtelivr'=>$row['PRDREC_QTEE'], 'typeemballage'=>$row['PRDREC_TYPEEMB'],'colissage'=>$row['PRDREC_COLISSAGE'],'unite'=>$row['PRDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:validreconditionnement.php?selectedTab=bds&rst=1');
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
		$sql = "UPDATE `recondit` SET  REC_VALIDE=2, REC_DATEVALID='".addslashes(date('Y-m-d'))."' WHERE `ID_DECLASS` = '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2, MVT_DATEVALID='".addslashes(date('Y-m-d'))."'  WHERE (MVT_NATURE LIKE 'RECONDITIONNEMENT%') AND ID_SOURCE='".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'un bon de sortie ('.$xid.', '.$oldcode.')'); //updateLog($username, $idcust, $action='' )
		//echo $sql;
		header('location:reconditionnement.php?selectedTab=bds&rst=1');
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
			$split = preg_split('/ /',$val);
			$sql = "DELETE FROM  `recondit` WHERE `ID_RECONDIT` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` = '".addslashes($split[0])."' AND
			(MVT_NATURE LIKE 'RECONDITIONNEMENT SORTANT' OR MVT_NATURE LIKE 'RECONDITIONNEMENT ENTRANT')";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:reconditionnement.php?selectedTab=bds&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

elseif($myaction =='' && $do ='') header('location:../index.php');

?>
