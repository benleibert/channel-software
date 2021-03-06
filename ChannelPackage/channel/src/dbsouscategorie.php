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
		(isset($_POST['categorie']) && $_POST['categorie']!='' 	? $categorie = trim($_POST['categorie']) 	: $categorie = '');
		(isset($_POST['codesouscategorie']) && $_POST['codesouscategorie']!='' ? $codesouscategorie = trim($_POST['codesouscategorie']) 	: $codesouscategorie = '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='' 	? $souscategorie 	= trim($_POST['souscategorie']) : $souscategorie = '');

		//SQL
		$sql  = "INSERT INTO `souscategorie` (`CODE_SOUSCATEGORIE`,  `CODE_CATEGORIE` ,`SOUSCAT_LIBELLE`) VALUES ('".addslashes($codesouscategorie)."', '".addslashes($categorie)."', '".addslashes($souscategorie)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une sous-catégorie ('.$codesouscategorie.', '.$souscategorie.')'); //updateLog($username, $idcust, $action='' )
		header('location:souscategorie.php?selectedTab=par&rs=1');
		break;

	//UPDATE CATEGORIE
	case 'update':
		(isset($_POST['oldsouscodecategorie']) && $_POST['oldsouscodecategorie']!='' ? $oldsouscodecategorie = trim($_POST['oldsouscodecategorie']) : $oldsouscodecategorie = '');
		(isset($_POST['categorie']) && $_POST['categorie']!='' 	? $categorie = trim($_POST['categorie']) 	: $categorie = '');
		(isset($_POST['codesouscategorie']) && $_POST['codesouscategorie']!='' ? $codesouscategorie = trim($_POST['codesouscategorie']) 	: $codesouscategorie = '');
		(isset($_POST['souscategorie']) && $_POST['souscategorie']!='' 	? $souscategorie 	= trim($_POST['souscategorie']) : $souscategorie = '');
		//SQL
		$sql  = "UPDATE `souscategorie` SET `CODE_SOUSCATEGORIE`='".addslashes($codesouscategorie)."' ,
		 `CODE_CATEGORIE`='".addslashes($categorie)."' ,`SOUSCAT_LIBELLE`= '".addslashes($souscategorie)."'
		 WHERE CODE_SOUSCATEGORIE LIKE '".addslashes($oldsouscodecategorie)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'une sous-catégorie ('.$oldsouscodecategorie.', '.$categorie.' - '.$souscategorie.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:souscategorie.php?selectedTab=par&rs=2');
		break;



	case 'check':

		$msg = "";
		(isset($_POST['codesouscategorie']) && $_POST['codesouscategorie']!='' 		? $codecategorie = trim($_POST['codesouscategorie']) 	: $codecategorie = '');

		if($codecategorie !=''){
			$sql = "SELECT COUNT(CODE_CATEGORIE) AS NBRE FROM  `souscategorie` WHERE `CODE_SOUSCATEGORIE` LIKE '".addslashes($codecategorie)."'";
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
		 $sql = "SELECT * FROM  `souscategorie` WHERE `CODE_SOUSCATEGORIE` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_CAT'] = $row;
		header('location:editsouscategorie.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `souscategorie` WHERE `CODE_SOUSCATEGORIE` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:souscategorie.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
