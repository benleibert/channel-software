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
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datereversement']) && $_POST['datereversement']!=''  ? $datereversement = trim($_POST['datereversement']) : $datereversement = '');
		(isset($_POST['programmation']) && $_POST['programmation']!='0'  	? $programmation = trim($_POST['programmation']) 	: $programmation = '');

		//Data
		$_SESSION['DATA_REV']=array(
		'exercice'=>$exercice,
		'datereversement'=>$datereversement,
		'programmation'=>$programmation,
		'nbreLigne'=>0,
		'ligne'=>array()
		);

//		if(checkReversement($beneficiaire,$programmation)>0) {
//			header('location:addreversement3.php?selectedTab=mvt');
//		}
//		else{
//
//		}
//
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}

		$sql = "SELECT * FROM prg_bareme INNER JOIN bareme ON (bareme.ID_BAREME=prg_bareme.ID_BAREME) WHERE ID_PROGR=".addslashes($programmation);
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REV']['ligne'] =array();
		$total = 0;
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$total += $row['PRG_REVERSEMENT']*1;
			array_push($_SESSION['DATA_REV']['ligne'],array('codeproduit'=>$row['ID_BAREME'], 'produit'=>$row['BAR_LIBELLE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['BAR_PRIX'], 'mixte'=>$row['PRG_MIXTE'] , 'ration1'=>$row['PRG_RATION1'] , 'ration2'=>$row['PRG_RATION2'], 'qte1'=>$row['PRG_QTE1'] , 'qte2'=>$row['PRG_QTE2'], 'nbreplat1'=>$row['NBRE_PLAT1'] , 'nbreplat2'=>$row['NBRE_PLAT2'], 'reversement'=>$row['PRG_REVERSEMENT'], 'mixte'=>$row['BAR_MIXTE']));
			$i++;
		}

		//Add line
		$_SESSION['DATA_REV']['nbreLigne'] =$i-1;
		$_SESSION['DATA_REV']['mnttotal'] =$total;
		$_SESSION['DATA_REV']['mntregle'] =totalReversement($programmation, $exercice);
		$_SESSION['DATA_REV']['mntrestant'] = $total - $_SESSION['DATA_REV']['mntregle'];

		if ($_SESSION['DATA_REV']['mntrestant'] == 0) {	header('location:addreversement2.php?selectedTab=prg'); }
		else header('location:addreversement1.php?selectedTab=prg');
		break;


	case 'add':
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datereversement']) && $_POST['datereversement']!=''  ? $datereversement = trim($_POST['datereversement']) : $datereversement = '');
		(isset($_POST['programmation']) && $_POST['programmation']!='0'  	? $programmation = trim($_POST['programmation']) 	: $programmation = '');
		$datereversement = mysqlFormat($datereversement);
		(isset($_POST['mnttotal']) && $_POST['mnttotal']!=''  	? $mnttotal = trim($_POST['mnttotal']) 	: $mnttotal = '');
		(isset($_POST['mntregle']) && $_POST['mntregle']!=''  	? $mntregle = trim($_POST['mntregle']) 	: $mntregle = '');
		(isset($_POST['mntverse']) && $_POST['mntverse']!=''  	? $mntverse = trim($_POST['mntverse']) 	: $mntverse = '');
		(isset($_POST['quittance']) && $_POST['quittance']!=''	? $quittance = trim($_POST['quittance']) 	: $quittance = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  		? $statut 	= trim($_POST['statut']) 	: $statut = '0');
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Insert
		$sql  = "INSERT INTO `reversement` (`ID_PROGR` ,`ID_EXERCICE` ,`REV_DATE` ,`REV_QUITTANCE` ,`REV_VALID` ,`REV_DATEVALID` ,`REV_MNTTOTAL` ,`REV_MNTVERSE`) ";
		$sql .= "VALUES ( '".addslashes($programmation)."', '".addslashes($exercice)."', '".addslashes($datereversement)."', '".addslashes($quittance)."','".addslashes($statut)."' , ";
		$sql .= "'".date('Y-m-d')."' , '".addslashes($mnttotal)."' , '".addslashes($mntverse)."') ;";

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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un reversement ('.$insert_id.', programmation '.$programmation.')'); //updateLog($username, $idcust, $action='' )

		unset($_SESSION['DATA_REV']);
		header('location:reversement.php?selectedTab=prg&rst=1');
		break;

	case 'update':
		(isset($_POST['xid']) && $_POST['xid']!=''				? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datereversement']) && $_POST['datereversement']!=''  ? $datereversement = trim($_POST['datereversement']) : $datereversement = '');
		(isset($_POST['programmation']) && $_POST['programmation']!='0'  	? $programmation = trim($_POST['programmation']) 	: $programmation = '');
		$datereversement = mysqlFormat($datereversement);
		(isset($_POST['mnttotal']) && $_POST['mnttotal']!=''  	? $mnttotal = trim($_POST['mnttotal']) 	: $mnttotal = '');
		(isset($_POST['mntregle']) && $_POST['mntregle']!=''  	? $mntregle = trim($_POST['mntregle']) 	: $mntregle = '');
		(isset($_POST['quittance']) && $_POST['quittance']!=''	? $quittance = trim($_POST['quittance']) 	: $quittance = '');
		(isset($_POST['mntverse']) && $_POST['mntverse']!=''  	? $mntverse = trim($_POST['mntverse']) 	: $mntverse = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');

		//Insert
		$sql  = "UPDATE `reversement` SET `ID_PROGR`='".addslashes($programmation)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`REV_DATE`='".addslashes($datereversement)."' ,`REV_VALID`='".addslashes($statut)."' ,";
		$sql .= "`REV_QUITTANCE`='".addslashes($quittance)."' ,`REV_DATEVALID`='".date('Y-m-d')."' ,`REV_MNTTOTAL`='".addslashes($mnttotal)."' ,`REV_MNTVERSE`='".addslashes($mntverse)."' WHERE ID_REVERSEMENT=$xid;";

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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un reversement ('.$xid.', programmation '.$programmation.')'); //updateLog($username, $idcust, $action='' )

		unset($_SESSION['DATA_REV']);
		header('location:reversement.php?selectedTab=prg&rst=1');

		break;


	case 'validate':
		(isset($_POST['xid']) && $_POST['xid']!=''				? $xid 	= trim($_POST['xid']) 			: $xid = '');
		(isset($_POST['exercice']) && $_POST['exercice']!=''				? $exercice 	= trim($_POST['exercice']) 			: $exercice = '');
		(isset($_POST['datereversement']) && $_POST['datereversement']!=''  ? $datereversement = trim($_POST['datereversement']) : $datereversement = '');
		(isset($_POST['programmation']) && $_POST['programmation']!='0'  	? $programmation = trim($_POST['programmation']) 	: $programmation = '');
		$datereversement = mysqlFormat($datereversement);
		(isset($_POST['mnttotal']) && $_POST['mnttotal']!=''  	? $mnttotal = trim($_POST['mnttotal']) 	: $mnttotal = '');
		(isset($_POST['mntregle']) && $_POST['mntregle']!=''  	? $mntregle = trim($_POST['mntregle']) 	: $mntregle = '');
		(isset($_POST['mntverse']) && $_POST['mntverse']!=''  	? $mntverse = trim($_POST['mntverse']) 	: $mntverse = '');
		(isset($_POST['quittance']) && $_POST['quittance']!=''  ? $quittance = trim($_POST['quittance']) 	: $quittance = '');
		(isset($_POST['statut']) && $_POST['statut']=='1'  				? $statut 		= trim($_POST['statut']) 			: $statut = '0');
		$magasin=$_SESSION['GL_USER']['MAGASIN'];
		$exercice =  $_SESSION['GL_USER']['EXERCICE'];

		//Insert
		$sql  = "UPDATE `reversement` SET `ID_PROGR`='".addslashes($programmation)."' ,`ID_EXERCICE`='".addslashes($exercice)."' ,`REV_DATE`='".addslashes($datereversement)."' ,`REV_VALID`='".addslashes($statut)."' ,";
		$sql .= "`REV_DATEVALID`='".date('Y-m-d')."' ,`REV_MNTTOTAL`='".addslashes($mnttotal)."' ,`REV_MNTVERSE`='".addslashes($mntverse)."' WHERE ID_REVERSEMENT=$xid;";

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
		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Validation d\'un reversement ('.$xid.', programmation '.$programmation.')'); //updateLog($username, $idcust, $action='' )

		unset($_SESSION['DATA_REV']);
		header('location:reversement.php?selectedTab=prg&rst=1');
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
		$sql = "SELECT reversement.*, programmation.CODE_MAGASIN FROM  `reversement` INNER JOIN programmation ON (programmation.ID_PROGR=reversement.ID_PROGR)  WHERE programmation.CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND `ID_REVERSEMENT` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$mnt = totalReversement($row['ID_PROGR'], $row['ID_EXERCICE']);

		$_SESSION['DATA_REV']=array(
		'xid'=>$row['ID_REVERSEMENT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereversement'=>frFormat2($row['REV_DATE']),
		'programmation'=>$row['ID_PROGR'],
		'mnttotal'=>$row['REV_MNTTOTAL'],
		'mntverse'=>$row['REV_MNTVERSE'],
		'quittance'=>$row['REV_QUITTANCE'],
		'mntregle'=>$mnt,
		'mntrestant'=>$row['REV_MNTTOTAL'] - $mnt,
		'statut'=>$row['REV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		$sql = "SELECT * FROM prg_bareme INNER JOIN bareme ON (bareme.ID_BAREME=prg_bareme.ID_BAREME) WHERE ID_PROGR= ".addslashes($row['ID_PROGR']);
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REV']['ligne'],array('codeproduit'=>$row['ID_BAREME'], 'produit'=>$row['BAR_LIBELLE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['BAR_PRIX'], 'mixte'=>$row['PRG_MIXTE'] , 'ration1'=>$row['PRG_RATION1'] , 'ration2'=>$row['PRG_RATION2'], 'qte1'=>$row['PRG_QTE1'] , 'qte2'=>$row['PRG_QTE2'], 'nbreplat1'=>$row['NBRE_PLAT1'] , 'nbreplat2'=>$row['NBRE_PLAT2'], 'reversement'=>$row['PRG_REVERSEMENT'], 'mixte'=>$row['BAR_MIXTE']));
		}
		$_SESSION['DATA_REV']['nbreLigne'] = $query->rowCount();
		header('location:detailreversement.php?selectedTab=prg&rst=1');
		break;

	default : ///Nothing
}
}elseif($myaction !='')
switch($myaction){
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

		$sql = "SELECT  reversement.*, programmation.CODE_MAGASIN FROM  `reversement` INNER JOIN programmation ON (programmation.ID_PROGR=reversement.ID_PROGR) WHERE programmation.CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND `ID_REVERSEMENT` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$mnt = totalReversement($row['ID_PROGR'], $row['ID_EXERCICE']);

		$_SESSION['DATA_REV']=array(
		'xid'=>$row['ID_REVERSEMENT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereversement'=>frFormat2($row['REV_DATE']),
		'programmation'=>$row['ID_PROGR'],
		'mnttotal'=>$row['REV_MNTTOTAL'],
		'mntverse'=>$row['REV_MNTVERSE'],
		'quittance'=>$row['REV_QUITTANCE'],
		'mntregle'=>$mnt,
		'mntrestant'=>$row['REV_MNTTOTAL'] - $mnt,
		'statut'=>$row['REV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		$sql = "SELECT * FROM prg_bareme INNER JOIN bareme ON (bareme.ID_BAREME=prg_bareme.ID_BAREME) WHERE ID_PROGR= ".addslashes($row['ID_PROGR']);
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REV']['ligne'],array('codeproduit'=>$row['ID_BAREME'], 'produit'=>$row['BAR_LIBELLE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['BAR_PRIX'], 'mixte'=>$row['PRG_MIXTE'] , 'ration1'=>$row['PRG_RATION1'] , 'ration2'=>$row['PRG_RATION2'], 'qte1'=>$row['PRG_QTE1'] , 'qte2'=>$row['PRG_QTE2'], 'nbreplat1'=>$row['NBRE_PLAT1'] , 'nbreplat2'=>$row['NBRE_PLAT2'], 'reversement'=>$row['PRG_REVERSEMENT'], 'mixte'=>$row['BAR_MIXTE']));
		}
		$_SESSION['DATA_REV']['nbreLigne'] = $query->rowCount();
		header('location:editreversement.php?selectedTab=prg&rst=1');
		break;

	case 'annul':
		(isset($_POST['xid']) ? $xid = $_POST['xid'] : $xid ='');
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		//TRANSFERT
		$sql = "UPDATE `reversement` SET  REV_VALID=2 WHERE `ID_REVERSEMENT` = '".addslashes($xid)."';";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Annulation d\'un réversement ('.$xid.', '.$xid.')'); //updateLog($username, $idcust, $action='' )
		header('location:reversement.php?selectedTab=prg&rst=1');
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

		$sql = "SELECT  reversement.*, programmation.CODE_MAGASIN FROM  `reversement` INNER JOIN programmation ON (programmation.ID_PROGR=reversement.ID_PROGR) WHERE programmation.CODE_MAGASIN LIKE '". $_SESSION['GL_USER']['MAGASIN']."' AND `ID_REVERSEMENT` = '".addslashes($id)."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$mnt = totalReversement($row['ID_PROGR'], $row['ID_EXERCICE']);

		$_SESSION['DATA_REV']=array(
		'xid'=>$row['ID_REVERSEMENT'],
		'exercice'=>$row['ID_EXERCICE'],
		'datereversement'=>frFormat2($row['REV_DATE']),
		'programmation'=>$row['ID_PROGR'],
		'mnttotal'=>$row['REV_MNTTOTAL'],
		'mntverse'=>$row['REV_MNTVERSE'],
		'quittance'=>$row['REV_QUITTANCE'],
		'mntregle'=>$mnt,
		'mntrestant'=>$row['REV_MNTTOTAL'] - $mnt,
		'statut'=>$row['REV_VALID'],
		'nbreLigne'=>0,
		'ligne'=>array()
		);

		$sql = "SELECT * FROM prg_bareme INNER JOIN bareme ON (bareme.ID_BAREME=prg_bareme.ID_BAREME) WHERE ID_PROGR= ".addslashes($row['ID_PROGR']);
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		//Ligne
		$_SESSION['DATA_REV']['ligne'] =array();
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			array_push($_SESSION['DATA_REV']['ligne'],array('codeproduit'=>$row['ID_BAREME'], 'produit'=>$row['BAR_LIBELLE'], 'unite'=>$row['ID_UNITE'], 'prix'=>$row['BAR_PRIX'], 'mixte'=>$row['PRG_MIXTE'] , 'ration1'=>$row['PRG_RATION1'] , 'ration2'=>$row['PRG_RATION2'], 'qte1'=>$row['PRG_QTE1'] , 'qte2'=>$row['PRG_QTE2'], 'nbreplat1'=>$row['NBRE_PLAT1'] , 'nbreplat2'=>$row['NBRE_PLAT2'], 'reversement'=>$row['PRG_REVERSEMENT'], 'mixte'=>$row['BAR_MIXTE']));
		}
		$_SESSION['DATA_REV']['nbreLigne'] = $query->rowCount();
		header('location:validreversement.php?selectedTab=prg&rst=1');
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
			$sql = "DELETE FROM  `reversement` WHERE `ID_REVERSEMENT` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:reversement.php?selectedTab=prg&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}
elseif($myaction =='' && $do ='') header('location:../index.php');

?>
