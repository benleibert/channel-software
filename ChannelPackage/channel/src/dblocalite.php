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
(isset($_GET['do']) &&  $_GET['do']!='' ? $do = $_GET['do'] : $do = '');

switch($do){
	//Log in User
	case 'add':
		(isset($_POST['grplocalite']) && $_POST['grplocalite']!='0'  ? $grplocalite = trim($_POST['grplocalite']) 	: $grplocalite = '');
		(isset($_POST['localite']) && $_POST['localite']!=''  ? $localite = trim($_POST['localite']) 	: $localite = '');
		(isset($_POST['dependance']) && $_POST['dependance']!=''  ? $dependance = trim($_POST['dependance']) 	: $dependance = '');

		//SQL
		$sql  = "INSERT INTO `localite` (`ID_GRPLOC` ,`LOC_NOM`, LOC_LIEN) VALUES ('".addslashes($grplocalite)."', '".addslashes($localite)."', '".addslashes($dependance)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une localité (, '.$localite.')'); //updateLog($username, $idcust, $action='' )
		header('location:localite.php?selectedTab=par&rs=1');
		break;

	case 'update':
		(isset($_POST['grplocalite']) && $_POST['grplocalite']!='0'  ? $grplocalite = trim($_POST['grplocalite']) 	: $grplocalite = '');
		(isset($_POST['localite']) && $_POST['localite']!=''  ? $localite = trim($_POST['localite']) 	: $localite = '');
		(isset($_POST['id']) && $_POST['id']!=''  ? $id = trim($_POST['id']) 	: $id = '');
		(isset($_POST['dependance']) && $_POST['dependance']!=''  ? $dependance = trim($_POST['dependance']) 	: $dependance = '');

		//SQL
		$sql  = "UPDATE `localite` SET `ID_GRPLOC`='".addslashes($grplocalite)."' ,`LOC_NOM`='".addslashes($localite)."' ,`LOC_LIEN`='".addslashes($dependance)."' WHERE ID_LOCALITE ='".addslashes($id)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une localité (, '.$localite.')'); //updateLog($username, $idcust, $action='' )
		header('location:localite.php?selectedTab=par&rs=2');
		break;


	default : ///Nothing
		//header('location:../index.php');

}

(isset($_POST['myaction']) && $_POST['myaction']!='' ? $myaction = $_POST['myaction'] : $myaction = '');
switch($myaction){

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
		$sql = "SELECT * FROM  `localite` WHERE `ID_LOCALITE` ='".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_LOC'] = $row;
		header('location:editlocalite.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `localite` WHERE `ID_LOCALITE` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:localite.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
