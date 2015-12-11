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
		(isset($_POST['codenatdeclassement']) && $_POST['codenatdeclassement']!='' 		? $codenatdeclassement = trim($_POST['codenatdeclassement']) 	: $codenatdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!='' 				? $natdeclassement 	= trim($_POST['natdeclassement']) 		: $natdeclassement = '');

		//SQL
		$sql  = "INSERT INTO `natdeclass` (`CODENATDECLASS` ,`LIBNATDECLASS`) VALUES ('".addslashes($codenatdeclassement)."', '".addslashes($natdeclassement)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une nature declassement ('.$codenatdeclassement.', '.$natdeclassement.')'); //updateLog($username, $idcust, $action='' )
		header('location:natdeclassement.php?selectedTab=par&rs=1');
		break;

	//UPDATE CATEGORIE
	case 'update':
		(isset($_POST['oldcodenatdeclassement']) && $_POST['oldcodenatdeclassement']!='' ? $oldcodenatdeclassement = trim($_POST['oldcodenatdeclassement']) : $oldcodenatdeclassement = '');
		(isset($_POST['codenatdeclassement']) && $_POST['codenatdeclassement']!='' 		? $codenatdeclassement = trim($_POST['codenatdeclassement']) 	: $codenatdeclassement = '');
		(isset($_POST['natdeclassement']) && $_POST['natdeclassement']!='' 				? $natdeclassement 	= trim($_POST['natdeclassement']) 		: $natdeclassement = '');

		//SQL
		$sql  = "UPDATE `natdeclass` SET `CODENATDECLASS`='".addslashes($codenatdeclassement)."' ,`LIBNATDECLASS`= '".addslashes($natdeclassement)."' WHERE CODENATDECLASS LIKE '".addslashes($oldcodenatdeclassement)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'une nature declassement ('.$oldcodenatdeclassement.', '.$codenatdeclassement.', '.$natdeclassement.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_CAT']);
		header('location:natdeclassement.php?selectedTab=par&rs=2');
		break;

	//SEARCH CATEGORIE

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODENATDECLASS) AS NBRE FROM  `natdeclass` WHERE `CODENATDECLASS` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code nature du déclassement.';}
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
		$sql = "SELECT * FROM  `natdeclass` WHERE `CODENATDECLASS` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_NAT'] = $row;
		header('location:editnatdeclassement.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `natdeclass` WHERE `CODENATDECLASS` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:natdeclassement.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
