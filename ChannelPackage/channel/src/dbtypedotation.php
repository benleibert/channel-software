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
	//Log in User
	case 'add':
		(isset($_POST['codetypedotation']) && $_POST['codetypedotation']!=''  	? $codetypedotation 	= trim($_POST['codetypedotation']) 	: $codetypedotation = '');
		(isset($_POST['typedotation']) && $_POST['typedotation']!=''  		? $typedotation 		= trim($_POST['typedotation']) 		: $typedotation = '');
		//SQL
		$sql  = "INSERT INTO `nomdotation` (`CODE_NDOTATION` ,`NDOT_LIBELLE`) VALUES ('".addslashes($codetypedotation)."', '".addslashes($typedotation)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de dotation ('.$codetypedotation.', '.$typedotation.')'); //updateLog($username, $idcust, $action='' )
		header('location:typedotation.php?selectedTab=par&rst=1');
		break;

	//UPDATE DOTATION
	case 'update':
		(isset($_POST['oldcodetypedotation']) && $_POST['oldcodetypedotation']!='' ? $oldcodetypedotation = trim($_POST['oldcodetypedotation']) : $oldcodetypedotation = '');
		(isset($_POST['codetypedotation']) && $_POST['codetypedotation']!='' 	? $codetypedotation = trim($_POST['codetypedotation']) 	: $codetypedotation = '');
		(isset($_POST['typedotation']) && $_POST['typedotation']!='' 				? $typedotation 	= trim($_POST['typedotation']) 		: $typedotation = '');

		//SQL
		$sql  = "UPDATE `nomdotation` SET `CODE_NDOTATION`='".addslashes($codetypedotation)."' ,`NDOT_LIBELLE`='".addslashes($typedotation)."' WHERE CODE_NDOTATION LIKE '".addslashes($oldcodetypedotation)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un type de dotation ('.$oldcodetypedotation.', '.$codetypedotation.' - '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:typedotation.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codetypedotation']) && $_POST['codetypedotation']!='' 		? $codetypedotation = trim($_POST['codetypedotation']) 	: $codetypedotation = '');

		if($codetypedotation !=''){
			$sql = "SELECT COUNT(CODE_NDOTATION) AS NBRE FROM  `nomdotation` WHERE `CODE_NDOTATION` LIKE '".addslashes($codetypedotation)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code type dotation.';}
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
		$sql = "SELECT * FROM  `nomdotation` WHERE `CODE_NDOTATION` LIKE  '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_DOT'] = $row;
		header('location:edittypedotation.php?selectedTab=par&rst=1');
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
			$sql = "DELETE FROM  `nomdotation` WHERE `CODE_NDOTATION` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:typedotation.php?selectedTab=par&rst=1');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
