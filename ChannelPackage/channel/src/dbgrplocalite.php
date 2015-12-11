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
		(isset($_POST['codegrplocalite']) && $_POST['codegrplocalite']!=''  ? $codegrplocalite = trim($_POST['codegrplocalite']) 	: $codegrplocalite = '');
		(isset($_POST['grplocalite']) && $_POST['grplocalite']!=''  ? $grplocalite = trim($_POST['grplocalite']) 	: $grplocalite = '');
		(isset($_POST['dependance']) && $_POST['dependance']!=''  ? $dependance = trim($_POST['dependance']) 	: $dependance = '');
			//SQL
		$sql  = "INSERT INTO `groupelocalite` (`ID_GRPLOC` ,`GRPLOC_LIBELLE` ,`GRPLOC_LIEN`) VALUES ('".addslashes($codegrplocalite)."', '".addslashes($grplocalite)."', '".addslashes($dependance)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de localité ('.$codegrplocalite.', '.$grplocalite.')'); //updateLog($username, $idcust, $action='' )
		header('location:grplocalite.php?selectedTab=par&rst=1');
		break;


	case 'check':

		$msg = "";
		(isset($_POST['codegrplocalite']) && $_POST['codegrplocalite']!='' ? $codegrplocalite= trim($_POST['codegrplocalite']) 	: $codegrplocalite = '');

		if($codegrplocalite !=''){
			$sql = "SELECT COUNT(ID_GRPLOC) AS NBRE FROM  `groupelocalite` WHERE `ID_GRPLOC` LIKE '".addslashes($codegrplocalite)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code type localite.';}
		}
		echo $msg;
		break;

	case 'update':
		(isset($_POST['oldcodegrplocalite']) && $_POST['oldcodegrplocalite']!=''  ? $oldcodegrplocalite = trim($_POST['oldcodegrplocalite']) 	: $oldcodegrplocalite = '');
		(isset($_POST['codegrplocalite']) && $_POST['codegrplocalite']!=''  ? $codegrplocalite = trim($_POST['codegrplocalite']) 	: $codegrplocalite = '');
		(isset($_POST['grplocalite']) && $_POST['grplocalite']!=''  ? $grplocalite = trim($_POST['grplocalite']) 	: $grplocalite = '');
		(isset($_POST['dependance']) && $_POST['dependance']!=''  ? $dependance = trim($_POST['dependance']) 	: $dependance = '');
			//SQL
		$sql  = "UPDATE `groupelocalite` SET `ID_GRPLOC`='".addslashes($codegrplocalite)."' ,`GRPLOC_LIBELLE`='".addslashes($grplocalite)."' ,`GRPLOC_LIEN`='".addslashes($dependance)."'  WHERE ID_GRPLOC LIKE '".addslashes($oldcodegrplocalite)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de localité ('.$codegrplocalite.', '.$grplocalite.')'); //updateLog($username, $idcust, $action='' )
		header('location:grplocalite.php?selectedTab=par&rst=1');
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
		$sql = "SELECT * FROM  `groupelocalite` WHERE `ID_GRPLOC` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_GL'] = $row;
		header('location:editgrplocalite.php?selectedTab=par&rst=1');
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
			$sql = "DELETE FROM  `groupelocalite` WHERE `ID_GRPLOC` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:grplocalite.php?selectedTab=par');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
