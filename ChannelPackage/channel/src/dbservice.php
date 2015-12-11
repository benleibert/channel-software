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
		(isset($_POST['codeservice']) && $_POST['codeservice']!='' 	? $codeservice 	= trim($_POST['codeservice']) 	: $codeservice = '');
		(isset($_POST['service']) && $_POST['service']!='' 			? $service		= trim($_POST['service']) 		: $service = '');
		(isset($_POST['province']) && $_POST['province']!='' 		? $province		= trim($_POST['province']) 		: $province = '');
		(isset($_POST['ville']) && $_POST['ville']!='' 				? $ville		= trim($_POST['ville']) 		: $ville = '');
		(isset($_POST['telephone']) && $_POST['telephone']!='' 		? $telephone	= trim($_POST['telephone']) 	: $telephone = '');
		(isset($_POST['email']) && $_POST['email']!='' 				? $email		= trim($_POST['email']) 		: $email = '');

		//SQL
		$sql  = "INSERT INTO `magasin` (`CODE_MAGASIN` ,`IDPROVINCE` ,`SER_NOM` ,`SER_EMAIL` ,`SER_TEL` ,`SER_VILLE`)
		VALUES ( '".addslashes($codeservice)."', '".addslashes($province)."', '".addslashes($service)."', '".addslashes($email)."' ,
		'".addslashes($telephone)."' ,'".addslashes($ville)."' )";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un service ('.$codeservice.', '.$service.')'); //updateLog($username, $idcust, $action='' )
		header('location:service.php?selectedTab=par&rs=1');
		break;

	case 'update':
		(isset($_POST['oldcodeservice']) && $_POST['oldcodeservice']!='' 	? $oldcodeservice 	= trim($_POST['oldcodeservice']) : $oldcodeservice = '');
		(isset($_POST['codeservice']) && $_POST['codeservice']!='' 	? $codeservice 	= trim($_POST['codeservice']) : $codeservice = '');
		(isset($_POST['service']) && $_POST['service']!='' 			? $service		= trim($_POST['service']) 		: $service = '');
		(isset($_POST['province']) && $_POST['province']!='' 		? $province		= trim($_POST['province']) 		: $province = '');
		(isset($_POST['ville']) && $_POST['ville']!='' 				? $ville		= trim($_POST['ville']) 		: $ville = '');
		(isset($_POST['telephone']) && $_POST['telephone']!='' 		? $telephone	= trim($_POST['telephone']) 	: $telephone = '');
		(isset($_POST['email']) && $_POST['email']!='' 				? $email		= trim($_POST['email']) 		: $email = '');

		//SQL
	  	$sql  = "UPDATE  `magasin` SET `CODE_MAGASIN`='".addslashes($codeservice)."' ,`IDPROVINCE`='".addslashes($province)."' ,
	  	`SER_NOM`= '".addslashes($service)."',`SER_EMAIL`='".addslashes($email)."' ,`SER_TEL`='".addslashes($telephone)."' ,
	  	`SER_VILLE`='".addslashes($ville)."'  WHERE CODE_MAGASIN LIKE '".addslashes($oldcodeservice)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un service ('.$codeservice.', '.$service.')'); //updateLog($username, $idcust, $action='' )
		header('location:service.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['code']) && $_POST['code']!='' 		? $code = trim($_POST['code']) 	: $code = '');

		if($code !=''){
			$sql = "SELECT COUNT(CODE_MAGASIN) AS NBRE FROM  `magasin` WHERE `CODE_MAGASIN` LIKE '".addslashes($code)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code service.';}
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
		$sql = "SELECT * FROM  `magasin` WHERE `CODE_MAGASIN` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_MAG'] = $row;
		header('location:editservice.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `magasin` WHERE `CODE_MAGASIN` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:service.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
