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
		(isset($_POST['region']) && $_POST['region']!='' 	? $region 	= trim($_POST['region']) 	: $region = '');
		(isset($_POST['province']) && $_POST['province']!='' 	? $province 	= trim($_POST['province']) 	: $province = '');

		//SQL
		$sql  = "INSERT INTO `province` (`IDREGION`, `PROVINCE`) VALUES ('".addslashes($region)."', '".addslashes($province)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une province ('.$province.')'); //updateLog($username, $idcust, $action='' )
		header('location:province.php?selectedTab=par&rs=1');
		break;

	//UPDATE CATEGORIE

	case 'update':
		(isset($_POST['oldidprovince']) && $_POST['oldidprovince']!='' ? $oldidprovince = trim($_POST['oldidprovince']) : $oldidprovince = '');
		(isset($_POST['region']) && $_POST['region']!='' 	? $region 	= trim($_POST['region']) 	: $region = '');
		(isset($_POST['province']) && $_POST['province']!='' 	? $province 	= trim($_POST['province']) 	: $province = '');


		//SQL
		$sql  = "UPDATE `province` SET `IDREGION`='".addslashes($region)."', `PROVINCE`='".addslashes($province)."' WHERE IDPROVINCE ='".addslashes($oldidprovince)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'une province ('.$province.')'); //updateLog($username, $idcust, $action='' )
		unset($GLOBALS['DATA_REG']);
		header('location:province.php?selectedTab=par&rs=2');
		break;

	//SEARCH CATEGORIE

	case 'search':
		$where ="";
		(isset($_POST['region']) && $_POST['region']!='' 	? $region 	= trim($_POST['region']) 	: $region = '');
		if($where != '')  $where = substr($where,0, strlen($where)-4);

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'une province ('.$codecategorie.', '.$categorie.')'); //updateLog($username, $idcust, $action='' )
		header('location:province.php?selectedTab=par&rst=1');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codecategorie']) && $_POST['codecategorie']!='' 	? $codecategorie = trim($_POST['codecategorie']) 	: $codecategorie = '');

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
		$sql = "SELECT * FROM  `province` WHERE `IDPROVINCE` = '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_PRO'] = $row;
		header('location:editprovince.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `province` WHERE `IDPROVINCE` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:province.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
