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

switch($do){
	//ADD sousgroupe
	case 'add':
		(isset($_POST['codesousgroupe']) && $_POST['codesousgroupe']!='' 		? $codesousgroupe = trim($_POST['codesousgroupe']) 	: $codesousgroupe = '');
		(isset($_POST['sousgroupe']) && $_POST['sousgroupe']!='' 				? $sousgroupe 	= trim($_POST['sousgroupe']) 		: $sousgroupe = '');

		//SQL
		$sql  = "INSERT INTO `sousgroupe` (`CODESOUSGROUP` ,`SOUSGROUPE`) VALUES ('".addslashes($codesousgroupe)."', '".addslashes($sousgroupe)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une catégorie ('.$codesousgroupe.', '.$sousgroupe.')'); //updateLog($username, $idcust, $action='' )
		header('location:sousgroupe.php?selectedTab=par&rs=1');
		break;

	//UPDATE sousgroupe

	case 'update':
		(isset($_POST['oldcodesousgroupe']) && $_POST['oldcodesousgroupe']!='' ? $oldcodesousgroupe = trim($_POST['oldcodesousgroupe']) : $oldcodesousgroupe = '');
		(isset($_POST['codesousgroupe']) && $_POST['codesousgroupe']!='' 		? $codesousgroupe = trim($_POST['codesousgroupe']) 	: $codesousgroupe = '');
		(isset($_POST['sousgroupe']) && $_POST['sousgroupe']!='' 				? $sousgroupe 	= trim($_POST['sousgroupe']) 		: $sousgroupe = '');

		//SQL
		$sql  = "UPDATE `sousgroupe` SET `CODESOUSGROUP`='".addslashes($codesousgroupe)."' ,`SOUSGROUPE`= '".addslashes($sousgroupe)."'
		WHERE CODESOUSGROUP LIKE '".addslashes($oldcodesousgroupe)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un sous-groupe ('.$oldcodesousgroupe.', '.$codesousgroupe.' - '.$sousgroupe.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:sousgroupe.php?selectedTab=par&rs=2');
		break;

	//SEARCH sousgroupe

	case 'search':
		$where ="";
		(isset($_POST['codesousgroupe']) && $_POST['codesousgroupe']!='' 	? 	$where .="sousgroupe.CODESOUSGROUP LIKE '".addslashes(trim($_POST['codesousgroupe']))."' AND " 	: $where .="");
		(isset($_POST['sousgroupe']) && $_POST['sousgroupe']!='' 			? 	$where .="sousgroupe.SOUSGROUPE LIKE '".addslashes(trim($_POST['sousgroupe']))."%'" 	: $where .="");

		if($where != '')  $where = substr($where,0, strlen($where)-4);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un sous-groupe ('.$codesousgroupe.', '.$sousgroupe.')'); //updateLog($username, $idcust, $action='' )
		header('location:sousgroupe.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codesousgroupe']) && $_POST['codesousgroupe']!='' 		? $codesousgroupe = trim($_POST['codesousgroupe']) 	: $codesousgroupe = '');

		if($codesousgroupe !=''){
			$sql = "SELECT COUNT(CODESOUSGROUP) AS NBRE FROM  `sousgroupe` WHERE `CODESOUSGROUP` LIKE '".addslashes($codesousgroupe)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code sous-groupe.';}
		}
		echo $msg;
		break;

	default : ///Nothing
		//header('location:../index.php');

}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
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
		$sql = "SELECT * FROM  `sousgroupe` WHERE `CODESOUSGROUP` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_CAT'] = $row;
		header('location:editsousgroupe.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `sousgroupe` WHERE `CODESOUSGROUP` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:sousgroupe.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
