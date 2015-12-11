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
//This variable $act say what to do (add, delete, ...)
(isset($_GET['do']) && $_GET['do']!='' ? $do = $_GET['do'] : $do = '');
(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');

if($myaction =='' && $do !=''){
switch($do){
	//Log in User
	case 'next':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 		: $exercice 		= '');
		(isset($_POST['livraison']) && $_POST['livraison']!='0'  			? $livraison		= trim($_POST['livraison']) 		: $livraison 		= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 		: $statut 			= '0');

		$sql = "SELECT * FROM livraison WHERE ID_LIVRAISON=$livraison";
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
		//Data
		$_SESSION['DATA_LOT']=array(
		'exercice'=>$exercice,
		'livraison'=>$livraison,
		'codelivraison'=>$row['CODE_LIVRAISON'],
		'libelle'=>$row['LVR_LIBELLE'],
		'datelivraison'=>$row['LVR_DATE'],
		'nbreLigne'=>$nbreLigne
		);

		header('location:addlot1.php?selectedTab=bde');
		break;

	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 		= trim($_POST['exercice']) 			: $exercice 		= '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison 	= trim($_POST['datelivraison'])		: $datelivraison 	= '');
		(isset($_POST['livraison']) && $_POST['livraison']!='0'  		? $livraison 		= trim($_POST['livraison'])			: $livraison		= '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison 	= trim($_POST['codelivraison']) 	: $codelivraison 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 			: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 		: $nbreLigne 		= '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 			= trim($_POST['statut']) 			: $statut 			= '0');

		$datelivraison = mysqlFormat($datelivraison);
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Data
		$_SESSION['DATA_LOT']=array(
		'exercice'=>$exercice,
		'datelivraison'=>$datelivraison,
		'livraison'=>$livraison,
		'codelivraison'=>$codelivraison,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		//Collect Data
		$sql1 ="";
		$sql2 ="";
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			(isset($_POST['reflot'.$i]) 	? $reflot 		= $_POST['reflot'.$i] 		: $reflot 		= '');
			(isset($_POST['dateperempt'.$i])? $dateperempt 	= $_POST['dateperempt'.$i] 	: $dateperempt 	= '');

			$dateperempt = mysqlFormat($dateperempt);

			if($codeproduit!='' && $produit!='' && $qte!='' && $reflot!='' && $dateperempt!='') {
				//Insert
				$sql1  .= "INSERT INTO `lot` (`REF_LOT` ,`ID_LIVRAISON` ,`CODE_PRODUIT` ,`DATE_PEREMPTION` ,`LOT_PRDQTE` , LOT_UNITE,
				`LOT_ETAT` ,`LOT_VALIDE`) VALUES ( '".addslashes($reflot)."','".addslashes($livraison)."','".addslashes($codeproduit)."',
	 			'".addslashes($dateperempt)."',	'".addslashes($qte)."', '".addslashes($unite)."','0' , '0');";

				$sql2 .="INSERT INTO estock (`CODE_PRODUIT`, `ID_EXERCICE`, `QTE_ESTOCK`, `ESTOCK_UNITE`, `DATE_ESTOCK`, `NATURE_ESTOCK`, `NATURE_IDESTOCK`,
				`ESREFLOT`, `ESDATEPEREMP`) VALUES ('".addslashes($codeproduit)."','".addslashes($exercice)."',	'".addslashes($qte)."','".addslashes($unite)."',
				'".addslashes(date('Y-m-d H:i:s'))."' ,'LIVRAISON' ,	'".addslashes($livraison)."' , '".addslashes($reflot)."','".addslashes($dateperempt)."') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			$insert_id =  $cnx->lastInsertId();
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout des lignes de lots('.$insert_id.', livraison n° '.$codelivraison.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			$insert_id =  $cnx->lastInsertId();
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Ajout stock entrant (".$insert_id.', livraison n° '.$codelivraison.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo  $sql2;
		unset($_SESSION['DATA_LOT']);
		header('location:lots.php?selectedTab=bde&rst=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 			= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''			? $exercice 	= trim($_POST['exercice']) 		: $exercice = '');
		(isset($_POST['datelivraison']) && $_POST['datelivraison']!=''  ? $datelivraison = trim($_POST['datelivraison']): $datelivraison = '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 			: $libelle 			= '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 	= trim($_POST['commande']) 			: $commande = '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison = trim($_POST['codelivraison']) 	: $codelivraison = '');
		(isset($_POST['statut']) && $_POST['statut']!=''  				? $statut 		= trim($_POST['statut']) 			: $statut = '');

		$datelivraison = mysqlFormat($datelivraison);
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		if($commande != ''){
			//Insert
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`='".addslashes($commande)."' ,`LVR_LIBELLE`='".addslashes($libelle)."',	`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
		}
		else {
			$sql  = "UPDATE `livraison` SET `ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_LIVRAISON`='".addslashes($codelivraison)."',
			`ID_COMMANDE`=NULL ,`LVR_DATE`='".addslashes($datelivraison)."' ,`LVR_LIBELLE`='".addslashes($libelle)."', `LVR_VALIDE`='".addslashes($statut)."'
			WHERE ID_LIVRAISON='$xid'";
		}

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'une livraison (".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )

		//Data
		$_SESSION['DATA_LOT']['exercice']=$exercice;
		$_SESSION['DATA_LOT']['datelivraison']=$datelivraison;

		//Collect Data
		$sql1 ="";
		$sql2 ="";
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['oldcodeproduit'.$i])? $oldcodeproduit 	= $_POST['oldcodeproduit'.$i] 	: $oldcodeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qterecu'.$i]) 	? $qterecu 		= $_POST['qterecu'.$i] 		: $qterecu 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($oldcodeproduit!='' && $codeproduit!='' && $produit!='' && $qte!='') {
				if($commande != ''){
					$sql1 .="UPDATE `prd_livraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVRPRD_QUANTITE`='".addslashes($qte)."' ,
					`LVRPRD_RECU`='".addslashes($qterecu)."', `LVR_UNITE`='".addslashes($unite)."', `LVR_MAGASIN`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`='".addslashes($commande)."'  WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_LIVRAISON='$xid' ;";
				}
				else{
					$sql1 .="UPDATE `prd_livraison` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`LVRPRD_QUANTITE`='".addslashes($qte)."' ,
					`LVRPRD_RECU`='".addslashes($qterecu)."', `LVR_UNITE`='".addslashes($unite)."', `LVR_MAGASIN`='".addslashes($magasin)."',
					`LVR_IDCOMMANDE`=NULL WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND ID_LIVRAISON='$xid' ;";
				}

				$sql2 .="UPDATE `mouvement` SET `CODE_PRODUIT`='".addslashes($codeproduit)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,
				`MVT_DATE`='".addslashes($datelivraison)."' ,`MVT_TIME`='".addslashes(date('H:i:s'))."' ,`MVT_QUANTITE`='".addslashes($qterecu)."' ,`MVT_UNITE`='".addslashes($unite)."',
				`MVT_VALID`='$statut', `MVT_TYPE`='E'	WHERE `CODE_PRODUIT`='".addslashes($oldcodeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND ID_SOURCE='$xid'; ";

			}
			elseif($oldcodeproduit=='' && $codeproduit!='' && $produit!='' && $qte!='') {
				if($commande != ''){
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LVR_UNITE` ,
					`LVR_MAGASIN`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."','".addslashes($commande)."'); ";
				}
				else{
					$sql1 .="INSERT INTO `prd_livraison` (`ID_LIVRAISON` ,`CODE_PRODUIT` ,`LVRPRD_QUANTITE` ,`LVRPRD_RECU` ,`LVR_UNITE` ,
					`LVR_MAGASIN`,`LVR_IDCOMMANDE`) VALUES ('".addslashes($insert_id)."', '".addslashes($codeproduit)."', '".addslashes($qte)."' ,
					'".addslashes($qterecu)."', '".addslashes($unite)."','".addslashes($magasin)."',NULL); ";
				}

				$sql2 .="INSERT INTO `mouvement` (`ID_EXERCICE` ,`CODE_PRODUIT` ,`ID_SOURCE` ,`CODE_MAGASIN` ,	`MVT_DATE` ,`MVT_TIME` ,`MVT_QUANTITE` ,
				`MVT_UNITE` ,`MVT_NATURE` ,	`MVT_VALID`,`MVT_TYPE`) VALUES ('".addslashes($exercice)."','".addslashes($codeproduit)."',
				'".addslashes($insert_id)."', '".addslashes($magasin)."', '".addslashes($datelivraison)."' ,'".addslashes(date('H:i:s'))."' ,
				'".addslashes($qterecu)."' , '".addslashes($unite)."', 'LIVRAISON','$statut','E') ; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification des lignes de livraison('.$xid.', Livraison n°'.$codelivraison.')'); //updateLog($username, $idcust, $action='' )

			$query =  $cnx->prepare($sql2); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Modification d'un mouvement(".$xid.', livraison n°'.$codelivraison.')'); //updateLog($username, $idcust, $action='' )
		}
		unset($_SESSION['DATA_LOT']);
		header('location:livraison.php?selectedTab=bde&rs=2');
		break;

	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''						? $xid 				= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['codelivraison']) && $_POST['codelivraison']!=''  ? $codelivraison 	= trim($_POST['codelivraison']) : $codelivraison = '');
		(isset($_POST['commande']) && $_POST['commande']!='0'  			? $commande 		= trim($_POST['commande']) 		: $commande = '');

		//Insert
		$sql  = "UPDATE `livraison` SET `LVR_VALIDE`='1', `LVR_DATEVALID`='".date('Y-m-d H:i:s')."' WHERE ID_LIVRAISON='$xid'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'une livraison (".$xid.', Livraison n°'.$codelivraison.')'); //updateLog($username, $idcust, $action='' )

		//Collect Data
		$sql1 ="";
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['qterecu'.$i]) 	? $qterecu 		= $_POST['qterecu'.$i] 		: $qterecu 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			if($codeproduit!='' && $produit!='' && $qte!='') {
				$sql1 .="UPDATE `mouvement` SET  `MVT_VALID`='1', `MVT_TYPE`='E', `MVT_DATEVALID`='".date('Y-m-d H:i:s')."'
				WHERE `CODE_PRODUIT`='".addslashes($codeproduit)."' AND `MVT_NATURE`='LIVRAISON' AND ID_SOURCE='$xid'; ";
			}
		}

		if (($sql1 !='')) {
			$query =  $cnx->prepare($sql1); //Prepare the SQL
			$query->execute(); //Execute prepared SQL =>
			updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], "Validation d'un mouvement(".$xid.', commande n°'.$commande.')'); //updateLog($username, $idcust, $action='' )
		}
		//echo $sql1, $sql2;
		unset($_SESSION['DATA_LOT']);
		header('location:livraison.php?selectedTab=bde&rs=2');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LOT']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_LIVRAISON = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LOT']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'magasin'=>$row['LVR_MAGASIN']));
		}
		$_SESSION['DATA_LOT']['nbreLigne'] = $query->rowCount();
		header('location:detaillivraison.php?selectedTab=bde&rst=1');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data  CDE_STATUT
		$_SESSION['DATA_LOT']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0,
		'ligne'=>array(),
		'journal'=>array()
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT)
		WHERE ID_LIVRAISON = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LOT']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'magasin'=>$row['LVR_MAGASIN']));
		}
		$_SESSION['DATA_LOT']['nbreLigne'] = $query->rowCount();


		//LIGNES MOUVEMENT
		$sql = "SELECT * FROM `mouvement` WHERE MVT_NATURE LIKE 'LIVRAISON' AND ID_SOURCE = '".addslashes($id)."' ORDER BY CODE_PRODUIT ASC";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LOT']['journal'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LOT']['journal'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['MVT_QUANTITE'], 'qtelvr'=>$row['MVT_QUANTITE'], 'unite'=>$row['MVT_UNITE'], 'valide'=>$row['MVT_VALID']));
		}

		$_SESSION['DATA_LOT']['nbreLigne2'] = $query->rowCount();

		//print_r($_SESSION['DATA_LOT']['journal']);
		header('location:journallivraison.php?selectedTab=bde&rst=1');
		break;


	case 'check':
		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_LIVRAISON) AS NBRE FROM  `livraison` WHERE `CODE_LIVRAISON` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code livraison.';}
		}
		echo $msg;
		break;
	default : ///Nothing
		//header('location:../index.php');
}
}elseif($myaction !='')
switch($myaction){


	case 'addline':
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie 	= '');
		(isset($_POST['codebonsortie']) && $_POST['codebonsortie']!=''  ? $codebonsortie 	= trim($_POST['codebonsortie'])	: $codebonsortie 	= '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire	= '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire 	= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid				= '');

		//Data
		$_SESSION['DATA_LOT']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datebonsortie'=>$datebonsortie,
		'idbeneficiaire'=>$idbeneficiaire,
		'beneficiaire'=>$beneficiaire,
		'codebonsortie'=>$codebonsortie,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 		= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 			= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_LOT']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_LOT']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
			}
		}
		//Add line
		$_SESSION['DATA_LOT']['nbreLigne'] +=1;
		$_SESSION['DATA_LOT']['nbreLigne'] -=$moins;
		header('location:addbonsortie1.php?selectedTab=bds');
		break;

	case 'addline1':
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
		(isset($_POST['codebonsortie']) && $_POST['codebonsortie']!=''  ? $codebonsortie 	= trim($_POST['codebonsortie'])	: $codebonsortie = '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid= '');

		//Data
		$_SESSION['DATA_LOT']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datebonsortie'=>$datebonsortie,
		'idbeneficiaire'=>$idbeneficiaire,
		'beneficiaire'=>$beneficiaire,
		'codebonsortie'=>$codebonsortie,
		'libelle'=>$libelle,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$moins=0;
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');

			//Check if exite de produit
			$prdIndex = isExitePrd($codeproduit, $_SESSION['DATA_LOT']['ligne']);
			if($prdIndex != -1){ $_SESSION['DATA_LOT']['ligne'][$prdIndex]['qte'] += $qte; $moins++;	}
			else{//Add to list
				if($codeproduit!='' && $produit!='' && $qte!='') array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit, 'qte'=>$qte, 'unite'=>$unite));
			}
		}
		//Add line
		$_SESSION['DATA_LOT']['nbreLigne'] +=1;
		$_SESSION['DATA_LOT']['nbreLigne'] -=$moins;
		header('location:editbonsortie.php?selectedTab=bds');
		break;

	case 'delline':
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie 	= '');
		(isset($_POST['codebonsortie']) && $_POST['codebonsortie']!=''  ? $codebonsortie 	= trim($_POST['codebonsortie'])	: $codebonsortie 	= '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire 	= '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire		= '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle 			= '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne 		= '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection 	= trim($_POST['rowSelection']) 	: $rowSelection 	= '');
		(isset($_POST['xid']) && $_POST['xid']!=''  					? $xid 				= trim($_POST['xid']) 			: $xid				= '');

		$supp =0;
		//Data
		$_SESSION['DATA_LOT']=array(
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'codedeclassement'=>$codedeclassement,
		'raison'=> $raison,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite));
			}
			elseif($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection==$codeproduit){$supp++;}
		}
		//Add line
		$_SESSION['DATA_LOT']['nbreLigne'] -=$supp;
		header('location:addbonsortie1.php?selectedTab=bds');
		break;

	case 'delline1':
		(isset($_POST['xid']) && $_POST['xid']!=''			? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['datebonsortie']) && $_POST['datebonsortie']!=''  ? $datebonsortie 	= trim($_POST['datebonsortie']) : $datebonsortie = '');
		(isset($_POST['codebonsortie']) && $_POST['codebonsortie']!=''  ? $codebonsortie 	= trim($_POST['codebonsortie'])	: $codebonsortie = '');
		(isset($_POST['idbeneficiaire']) && $_POST['idbeneficiaire']!=''? $idbeneficiaire 	= trim($_POST['idbeneficiaire']): $idbeneficiaire = '');
		(isset($_POST['beneficiaire']) && $_POST['beneficiaire']!=''	? $beneficiaire 	= trim($_POST['beneficiaire'])	: $beneficiaire = '');
		(isset($_POST['libelle']) && $_POST['libelle']!='' 				? $libelle 			= trim($_POST['libelle']) 		: $libelle = '');
		(isset($_POST['nbreLigne']) && $_POST['nbreLigne']!=''  		? $nbreLigne 		= trim($_POST['nbreLigne']) 	: $nbreLigne = '');
		(isset($_POST['rowSelection']) && $_POST['rowSelection']!=''  	? $rowSelection = trim($_POST['rowSelection']) 		: $rowSelection = '');

		$supp =0;
		//Data
		$_SESSION['DATA_LOT']=array(
		'xid'=>$xid,
		'exercice'=>$exercice,
		'datedeclassement'=>$datedeclassement,
		'codedeclassement'=>$codedeclassement,
		'raison'=> $raison,
		'cabinet'=> $cabinet,
		'refrapport'=> $refrapport,
		'nbreLigne'=>$nbreLigne
		);

		//Collect Data
		$_SESSION['DATA_LOT']['ligne'] =array();
		for($i=1; $i<=$_SESSION['DATA_LOT']['nbreLigne']; $i++){
			(isset($_POST['codeproduit'.$i])? $codeproduit 	= $_POST['codeproduit'.$i] 	: $codeproduit 	= '');
			(isset($_POST['produit'.$i]) 	? $produit 		= $_POST['produit'.$i] 		: $produit 	= '');
			(isset($_POST['qte'.$i]) 		? $qte 			= $_POST['qte'.$i] 			: $qte 		= '');
			(isset($_POST['unite'.$i]) 		? $unite 		= $_POST['unite'.$i] 		: $unite 		= '');
			//Add to list
			if($codeproduit!='' && $produit!='' && $qte!='' && $rowSelection!=$codeproduit){
				array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$codeproduit, 'produit'=>$produit,  'qte'=>$qte, 'unite'=>$unite));
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
				$sql = "DELETE FROM  `declass_cnd` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND `ID_DECLASS` = '".addslashes($xid)."';
				DELETE FROM  `mouvement` WHERE CODE_PRODUIT='".addslashes($codeproduit)."' AND MVT_NATURE LIKE 'PERTE' AND `ID_SOURCE` = '".addslashes($xid)."';";
				$query =  $cnx->prepare($sql); //Prepare the SQL
				$query->execute(); //Execute prepared SQL => $query
			}
		}
		$_SESSION['DATA_LOT']['nbreLigne'] -=$supp;
		echo $sql;
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LOT']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'commande'=>$row['ID_COMMANDE'],
		'libelle'=>stripslashes($row['LVR_LIBELLE']),
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES LIVRAISONS
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit
		ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE ID_LIVRAISON = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LOT']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'mag'=>$row['magasin']));
		}
		$_SESSION['DATA_LOT']['nbreLigne'] = $query->rowCount();
		header('location:editlivraison.php?selectedTab=bde&rst=1');
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
		//LIVRAISON
		$sql = "UPDATE `livraison` SET  LVR_VALIDE=2 WHERE `ID_LIVRAISON` = '".addslashes($xid)."';
		UPDATE mouvement SET MVT_VALID=2 WHERE (MVT_NATURE LIKE 'LIVRAISON')
		AND ID_SOURCE='".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'une livraison ('.$xid.', '.$oldcode.')'); //updateLog($username, $idcust, $action='' )
		header('location:livraison.php?selectedTab=bde&rst=1');
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
		$sql = "SELECT * FROM  `livraison` WHERE CODE_MAGASIN LIKE '".$_SESSION['GL_USER']['MAGASIN']."' AND `ID_LIVRAISON` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		//Data
		$_SESSION['DATA_LOT']=array(
		'xid'=>$row['ID_LIVRAISON'],
		'exercice'=>$row['ID_EXERCICE'],
		'datelivraison'=>frFormat2($row['LVR_DATE']),
		'commande'=>$row['ID_COMMANDE'],
		'codelivraison'=>stripslashes($row['CODE_LIVRAISON']),
		'statut'=>$row['LVR_VALIDE'],
		'nbreLigne'=>0
		);

		//LIGNES COMMANDE
		$sql = "SELECT * FROM `prd_livraison` INNER JOIN produit
		ON (prd_livraison.CODE_PRODUIT LIKE produit.CODE_PRODUIT) WHERE ID_LIVRAISON = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_LOT']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_LOT']['ligne'], array('codeproduit'=>$row['CODE_PRODUIT'], 'produit'=>stripslashes($row['PRD_LIBELLE']), 'qte'=>$row['LVR_PRDQTE'], 'qtelvr'=>$row['LVR_PRDRECU'], 'unite'=>$row['LVR_UNITE'], 'mag'=>$row['magasin']));
		}
		$_SESSION['DATA_LOT']['nbreLigne'] = $query->rowCount();
		header('location:validlivraison.php?selectedTab=bde&rst=1');
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
			$sql = "DELETE FROM  `prd_livraison` WHERE `ID_LIVRAISON` = '".addslashes($split[0])."';
			DELETE FROM  `livraison` WHERE `ID_LIVRAISON` = '".addslashes($split[0])."';";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query

			//Suppression dans Mouvement
			$sql = "DELETE FROM  `mouvement` WHERE `ID_SOURCE` = '".addslashes($split[0])."' AND MVT_NATURE LIKE 'LIVRAISON'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:livraison.php?selectedTab=bde&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');
?>
