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

	case 'add':
		(isset($_POST['xid']) && $_POST['xid']!=''					? $xid 			= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''		? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie 	= trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['dateentree']) && $_POST['dateentree']!=''  	? $dateentree 	= trim($_POST['dateentree']) 		: $dateentree = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']): $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  			? $raison 	= trim($_POST['raison']) 				: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  	? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  			? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$dateentree = mysqlFormat($dateentree);
		$magasin='MAG0';

		//Insert
		$sql  = "UPDATE `recondit` SET `REC_DATERETOUR`='".addslashes($dateentree)."'  WHERE ID_RECONDIT='$xid'; ";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$insert_id =  $cnx->lastInsertId();
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'dateentree'=>$dateentree,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 =""; $sql2 ="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['qtelivr'.$i]) 	? $qtelivr 		= $_POST['qtelivr'.$i] 		: $qtelivr 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="INSERT INTO `recond_entre` (`ID_CONDIT` ,`ID_RECONDIT` ,`CNDREC_QTEE` ,`CNDREC_UNITEE` ,`CNDREC_VALIDEE`) ";
				$sql1 .="VALUES ( '".addslashes($codeproduit)."', '".addslashes($xid)."', '".addslashes($qtelivr)."' , '".addslashes($unite)."', '$statut'); ";

				$sql2 .="INSERT INTO `mouvement` (`ID_CONDIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`, `MVT_VALID`) ";
				$sql2 .="VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($datesortie)."' , '".addslashes($qtelivr)."' , 'RECONDITIONNEMENT ENTREE','".addslashes($unite)."', '$statut') ; ";
			}

			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qtelivr, 'unite'=>$unite));

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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

			try {
				$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
			}
			catch (PDOException $error) { //Treat error
				//("Erreur de connexion : " . $error->getMessage() );
				header('location:errorPage.php');
			}
			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un mouvement('.$xid.', reconditionnement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		header('location:reconditionnement.php?selectedTab=mvt&rst=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  				? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$datesortie = mysqlFormat($datesortie);
		$magasin = 'MAG0';

		//Insert
		$sql  = "UPDATE `recondit` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REC_RAISON`='".addslashes($raison)."' ,`REC_DATESORTIE`='".addslashes($datesortie)."'  ,`REC_VALIDE`='$statut' ,`CODE_RECOND`='".addslashes($codereconditionnement)."') ";
		$sql .= "WHERE ID_RECONDIT='$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 ="";$sql2="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE  `recond_sorte` SET `ID_CONDIT`='".addslashes($codeproduit)."' ,`ID_RECONDIT`='".addslashes($xid)."' ,`CNDREC_QTES`='".addslashes($qte)."' ,`CNDREC_UNITES`='".addslashes($unite)."' ";
				$sql1 .="WHERE ID_CONDIT='".addslashes($oldcodeproduit)."' AND ID_RECONDIT='$xid'; ";

				$sql2 .="UPDATE `mouvement` SET `ID_CONDIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."', ";
				$sql2 .="`ID_SOURCE`='".addslashes($xid)."' ,`MVT_DATE`='".addslashes($datesortie)."' ,`MVT_QUANTITE`='".addslashes($qte)."' ,`MVT_NATURE`='RECONDITIONNEMENT SORTIE',`MVT_UNITE`='".addslashes($unite)."',  ";
				$sql2 .="MVT_VALID='$statut' WHERE `ID_CONDIT`='".addslashes($oldcodeproduit)."' AND ID_SOURCE='$xid' AND MVT_NATURE='RECONDITIONNEMENT SORTIE';";

				//Add to list
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!=''){
				$sql1 .="INSERT INTO `recond_sorte` (`ID_CONDIT` ,`ID_RECONDIT` ,`CNDREC_QTES` ,`CNDREC_UNITES`) ";
				$sql1 .="VALUES ( '".addslashes($codeproduit)."', '".addslashes($xid)."', '".addslashes($qte)."' , '".addslashes($unite)."'); ";

				$sql2 .="INSERT INTO `mouvement` (`ID_CONDIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`,`MVT_VALID`) ";
				$sql2 .="VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($dateresortie)."' , '".addslashes($qte)."' , 'RECONDITIONNEMENT SORTIE','".addslashes($unite)."', '$statut'); ";

				//Add to list
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un mouvement('.$xid.', déclassement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		header('location:reconditionnement.php?selectedTab=mvt&rst=1');

		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''								? $xid 				= trim($_POST['xid']) 				: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codereconditionnement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  				? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$datesortie = mysqlFormat($datesortie);
		$magasin = 'MAG0';

		//Insert
		$sql  = "UPDATE `recondit` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`REC_RAISON`='".addslashes($raison)."' ,`REC_DATESORTIE`='".addslashes($datesortie)."'  ,`REC_VALIDE`='$statut' ,`CODE_RECOND`='".addslashes($codereconditionnement)."') ";
		$sql .= "WHERE ID_RECONDIT='$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un reconditionnement ('.$xid.', '.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		$sql1 ="";$sql2="";
		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE  `recond_sorte` SET `ID_CONDIT`='".addslashes($codeproduit)."' ,`ID_RECONDIT`='".addslashes($xid)."' ,`CNDREC_QTES`='".addslashes($qte)."' ,`CNDREC_UNITES`='".addslashes($unite)."' ";
				$sql1 .="WHERE ID_CONDIT='".addslashes($oldcodeproduit)."' AND ID_RECONDIT='$xid'; ";

				$sql2 .="UPDATE `mouvement` SET `ID_CONDIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."', ";
				$sql2 .="`ID_SOURCE`='".addslashes($xid)."' ,`MVT_DATE`='".addslashes($datesortie)."' ,`MVT_QUANTITE`='".addslashes($qte)."' ,`MVT_NATURE`='RECONDITIONNEMENT SORTIE',`MVT_UNITE`='".addslashes($unite)."',  ";
				$sql2 .="MVT_VALID='$statut' WHERE `ID_CONDIT`='".addslashes($oldcodeproduit)."' AND ID_SOURCE='$xid' AND MVT_NATURE='RECONDITIONNEMENT SORTIE';";

				//Add to list
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!=''){
				$sql1 .="INSERT INTO `recond_sorte` (`ID_CONDIT` ,`ID_RECONDIT` ,`CNDREC_QTES` ,`CNDREC_UNITES`) ";
				$sql1 .="VALUES ( '".addslashes($codeproduit)."', '".addslashes($xid)."', '".addslashes($qte)."' , '".addslashes($unite)."'); ";

				$sql2 .="INSERT INTO `mouvement` (`ID_CONDIT` ,`ID_EXERCICE` ,`CODE_MAGASIN` ,`ID_SOURCE` ,`MVT_DATE` ,`MVT_QUANTITE` ,`MVT_NATURE`,`MVT_UNITE`,`MVT_VALID`) ";
				$sql2 .="VALUES ('".addslashes($codeproduit)."', '".addslashes($exercice)."', '".addslashes($magasin)."', '".addslashes($xid)."', '".addslashes($dateresortie)."' , '".addslashes($qte)."' , 'RECONDITIONNEMENT SORTIE','".addslashes($unite)."', '$statut'); ";

				//Add to list
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
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
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un mouvement('.$xid.', déclassement n°'.$codereconditionnement.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_RECD']);
		header('location:reconditionnement.php?selectedTab=mvt&rst=1');

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
		$sql = "SELECT * FROM  `recondit` WHERE `ID_RECONDIT` = '".addslashes($id)."'";
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
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM `recond_sorte` WHERE ID_RECONDIT = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['ID_CONDIT'], 'produit'=>getConditionnement($row['ID_CONDIT']),  'qte'=>$row['CNDREC_QTES'], 'unite'=>$row['CNDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:detailreconditionnements.php?selectedTab=mvt&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!='' ? $codereconditionnement = trim($_POST['codereconditionnement']) : $codereconditionnement = '');

		if($codereconditionnement !=''){
			$sql = "SELECT COUNT(CODE_RECOND) AS NBRE FROM  `recondit` WHERE `CODE_RECOND` LIKE '".addslashes($codereconditionnement)."'";
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
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codedeclassement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');

		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
		'statut'=>$statut,
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		//Collect Data
		$_SESSION['DATA_RECD']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_RECD']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));

		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA']);
		header('location:addreconditionnements1.php?selectedTab=mvt');
		break;

	case 'addline1':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codedeclassement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 		= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
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
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));

		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] +=1;
		//print_r($_SESSION['DATA']);
		header('location:editreconditionnements.php?selectedTab=mvt');
		break;

	case 'delline':
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codedeclassement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  			? $rowSelection 	= trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_RECD']=array(
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
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
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_RECD']['nbreLigne'] -=$supp;
		header('location:addreconditionnements1.php?selectedTab=mvt');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''					? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datesortie']) && $_POST['datesortie']!=''  	? $datesortie = trim($_POST['datesortie']) 		: $datesortie = '');
		(isset($_POST['codereconditionnement']) && $_POST['codereconditionnement']!=''  	? $codereconditionnement = trim($_POST['codereconditionnement']) 		: $codedeclassement = '');
		(isset($_POST['raison']) && $_POST['raison']!=''  						? $raison 	= trim($_POST['raison']) 						: $raison = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  				? $nbreLigne 	= trim($_POST['nbreLigne']) 		: $nbreLigne = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_RECD']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datesortie'=>$datesortie,
		'codereconditionnement'=>$codereconditionnement,
		'raison'=> $raison,
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
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite));
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
				$sql = "DELETE FROM  `recond_sorte` WHERE ID_CONDIT='".addslashes($codeproduit)."' AND `ID_RECONDIT` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE ID_CONDIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'RECONDITIONNEMENT SORTIE' AND `ID_SOURCE` = '".addslashes($xid)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_RECD']['nbreLigne'] -=$supp;
		echo $sql;
		header('location:editreconditionnements.php?selectedTab=mvt');
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
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT
		$sql = "SELECT * FROM `recond_sorte` WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['ID_CONDIT'], 'produit'=>getConditionnement($row['ID_CONDIT']),  'qte'=>$row['CNDREC_QTES'], 'unite'=>$row['CNDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:editreconditionnements.php?selectedTab=mvt&rst=1');
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
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT
		$sql = "SELECT * FROM `recond_sorte` WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['ID_CONDIT'], 'produit'=>getConditionnement($row['ID_CONDIT']),  'qte'=>$row['CNDREC_QTES'], 'qtelivr'=>'', 'unite'=>$row['CNDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:recondreconditionnement.php?selectedTab=mvt&rst=1');
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
		$sql = "SELECT * FROM  `recondit` WHERE `ID_RECONDIT` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		//Data  CDE_STATUT
		$_SESSION['DATA_RECD']=array(
		'xid'=>$row['ID_RECONDIT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datesortie'=>frFormat2($row['REC_DATESORTIE']),
		'codereconditionnement'=>$row['CODE_RECOND'],
		'raison'=> $row['REC_RAISON'],
		'statut'=>$row['REC_VALIDE'],
		'nbreLigne'=>$nbreLigne,
		'ligne'=>array()
		);

		//LIGNES RECONDITIONNEMENT SORTIE
		$sql = "SELECT * FROM `recond_sorte` WHERE ID_RECONDIT = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_RECD']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_RECD']['ligne'], array('codeproduit'=>$row['ID_CONDIT'], 'produit'=>getConditionnement($row['ID_CONDIT']),  'qte'=>$row['CNDREC_QTES'], 'unite'=>$row['CNDREC_UNITES']));
		}
		$_SESSION['DATA_RECD']['nbreLigne'] = $query->rowCount();
		header('location:validreconditionnements.php?selectedTab=mvt&rst=1');
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
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` = '".addslashes($split[0])."' AND MVT_NATURE LIKE 'RECONDITIONNEMENT SORTIE'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:reconditionnement.php?selectedTab=mvt&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

elseif($myaction =='' && $do ='') header('location:../index.php');

?>
