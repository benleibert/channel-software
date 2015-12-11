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
	//ADD CATEGORIE
	case 'add':
		(isset($_POST['codecategorie']) && $_POST['codecategorie']!='' 		? $codecategorie = trim($_POST['codecategorie']) 	: $codecategorie = '');
		(isset($_POST['categorie']) && $_POST['categorie']!='' 				? $categorie 	= trim($_POST['categorie']) 		: $categorie = '');

		//SQL
		$sql  = "INSERT INTO `categorie` (`CODE_CATEGORIE` ,`CAT_LIBELLE`) VALUES ('".addslashes($codecategorie)."', '".addslashes($categorie)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une catégorie ('.$codecategorie.', '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		header('location:categorie.php?selectedTab=par&rst=1');
		break;

	//UPDATE CATEGORIE
	case 'update':
		(isset($_POST['oldcodecategorie']) && $_POST['oldcodecategorie']!='' ? $oldcodecategorie = trim($_POST['oldcodecategorie']) : $oldcodecategorie = '');
		(isset($_POST['codecategorie']) && $_POST['codecategorie']!='' 		? $codecategorie = trim($_POST['codecategorie']) 	: $codecategorie = '');
		(isset($_POST['categorie']) && $_POST['categorie']!='' 				? $categorie 	= trim($_POST['categorie']) 		: $categorie = '');

		//SQL
		$sql  = "UPDATE `categorie` SET `CODE_CATEGORIE`='".addslashes($codecategorie)."' ,`CAT_LIBELLE`= '".addslashes($categorie)."' WHERE CODE_CATEGORIE LIKE '".addslashes($oldcodecategorie)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'une catégorie ('.$oldcodecategorie.', '.$codecategorie.' - '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:categorie.php?selectedTab=par&rst=1');
		break;

	//SEARCH CATEGORIE
	case 'search':
		$where ="";
		(isset($_POST['codecategorie']) && $_POST['codecategorie']!='' 	? 	$where .="categorie.CODE_CATEGORIE LIKE '".addslashes(trim($_POST['codecategorie']))."' AND " 	: $where .="");
		(isset($_POST['categorie']) && $_POST['categorie']!='' 			? 	$where .="categorie.CAT_LIBELLE LIKE '".addslashes(trim($_POST['categorie']))."%'" 	: $where .="");

		if($where != '')  $where = substr($where,0, strlen($where)-4);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une catégorie ('.$codecategorie.', '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		header('location:categorie.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codecategorie']) && $_POST['codecategorie']!='' 		? $codecategorie = trim($_POST['codecategorie']) 	: $codecategorie = '');

		if($codecategorie !=''){
			$sql = "SELECT COUNT(CODE_CATEGORIE) AS NBRE FROM  `categorie` WHERE `CODE_CATEGORIE` LIKE '".addslashes($codecategorie)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code cat&eacute;gorie.';}
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
		$sql = "SELECT * FROM  `categorie` WHERE `CODE_CATEGORIE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_CAT'] = $row;
		header('location:editcategorie.php?selectedTab=par&rst=1');
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
			$sql = "DELETE FROM  `categorie` WHERE `CODE_CATEGORIE` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:categorie.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
