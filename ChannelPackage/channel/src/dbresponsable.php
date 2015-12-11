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
	//SERVICE
	case 'add':
		(isset($_POST['magasin']) && $_POST['magasin']!='0'  	? $magasin = trim($_POST['magasin']) 		: $magasin = '');
		(isset($_POST['personnel']) && $_POST['personnel']!='0'	? $personnel = trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $datedebut = trim($_POST['datedebut']) 	: $datedebut = '');
		(isset($_POST['datefin']) && $_POST['datefin']!=''  	? $datefin = trim($_POST['datefin']) 		: $datefin = '');
		$datedebut = mysqlFormat($datedebut);
		$datefin = mysqlFormat($datefin);

		//SQL
		$sql  = "INSERT INTO `respmagasin` (`NUM_MLLE` ,`CODE_MAGASIN` ,`RES_DATEDEBUT` ,`RES_DATEFIN`)
		VALUES ('".addslashes($personnel)."', '".addslashes($magasin)."' , '".addslashes($datedebut)."' , '".addslashes($datefin)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un responsable ('.$personnel.', '.$personnel.')'); //updateLog($username, $idcust, $action='' )
		header('location:responsable.php?selectedTab=par&rs=1');
		break;

	//SERVICE
	case 'update':
		(isset($_POST['magasin']) && $_POST['magasin']!='0'  	? $magasin = trim($_POST['magasin']) 		: $magasin = '');
		(isset($_POST['personnel']) && $_POST['personnel']!='0'	? $personnel = trim($_POST['personnel']) 	: $personnel = '');
		(isset($_POST['datedebut']) && $_POST['datedebut']!=''  ? $datedebut = trim($_POST['datedebut']) 	: $datedebut = '');
		(isset($_POST['datefin']) && $_POST['datefin']!=''  	? $datefin = trim($_POST['datefin']) 		: $datefin = '');
		$datedebut = mysqlFormat($datedebut);
		$datefin = mysqlFormat($datefin);
		(isset($_POST['id']) && $_POST['id']!=''  			? $id = trim($_POST['id']) 				: $id = '');

		//SQL
		$sql  = "UPDATE `respmagasin` SET `NUM_MLLE`='".addslashes($personnel)."' ,`CODE_MAGASIN`='".addslashes($magasin)."' ,`RES_DATEDEBUT`='".addslashes($datedebut)."' ,
		`RES_DATEFIN`='".addslashes($datefin)."'  WHERE ID_RESPO=$id";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Modification d\'un responsable ('.$personnel.', '.$magasin.')'); //updateLog($username, $idcust, $action='' )
		header('location:responsable.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['id']) && $_POST['id']!='' 		? $id = trim($_POST['id']) 	: $codemagasin = '');

		if($codemagasin !=''){
			$sql = "SELECT COUNT(CODE_MAGASIN) AS NBRE FROM  `service` WHERE `CODE_MAGASIN` LIKE '".addslashes($id)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code magasin.';}
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
		$split = preg_split('/ /',$id);
		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$sql = "SELECT * FROM  `respmagasin` WHERE `ID_RESPO` = ".addslashes($split[0]);
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_REP'] = $row;
		header('location:editresponsable.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `respmagasin` WHERE `ID_RESPO` = '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:responsable.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
