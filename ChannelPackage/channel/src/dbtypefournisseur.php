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
	//ADD TYPEfournisseur
	case 'add':
		(isset($_POST['codetypefournisseur']) && $_POST['codetypefournisseur']!=''  ? $codetypefournisseur = trim($_POST['codetypefournisseur']) 	: $codetypefournisseur = '');
		(isset($_POST['typefournisseur']) && $_POST['typefournisseur']!=''  ? $typefournisseur = trim($_POST['typefournisseur']) 	: $typefournisseur = '');

		//SQL
		echo $sql  = "INSERT INTO `typefournisseur` (`CODE_TYPEFOUR` ,`TYPEFOUR_NOM`) VALUES ('".addslashes($codetypefournisseur)."', '".addslashes($typefournisseur)."')";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de bénéficiaire ('.$typefournisseur.', '.$typefournisseur.')'); //updateLog($username, $idcust, $action='' )
		header('location:typefournisseur.php?selectedTab=par&rs=1');
		break;

	//UPDATE TYPEfournisseur

	case 'update':
		(isset($_POST['oldcodetypefournisseur']) && $_POST['oldcodetypefournisseur']!=''  ? $oldcodetypefournisseur = trim($_POST['oldcodetypefournisseur']) 	: $codetypefournisseur = '');
		(isset($_POST['codetypefournisseur']) && $_POST['codetypefournisseur']!=''  ? $codetypefournisseur = trim($_POST['codetypefournisseur']) 	: $codetypefournisseur = '');
		(isset($_POST['typefournisseur']) && $_POST['typefournisseur']!=''  ? $typefournisseur = trim($_POST['typefournisseur']) 	: $typefournisseur = '');

		//SQL
		echo $sql  = "UPDATE `typefournisseur` SET `CODE_TYPEFOUR`='".addslashes($codetypefournisseur)."' ,`TYPEFOUR_NOM`='".addslashes($typefournisseur)."' WHERE CODE_TYPEFOUR LIKE '".addslashes($oldcodetypefournisseur)."'";

		try {
			$cnx = new PDO(DBD, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); //Connexion to database
		}
		catch (PDOException $error) { //Treat error
			//("Erreur de connexion : " . $error->getMessage() );
			header('location:errorPage.php');
		}
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query

		updateLog($_SESSION['GL_USER']['MAGASIN'], $_SESSION['GL_USER']['LOGIN'],  $_SESSION['GL_USER']['MLLE'], 'Ajout d\'un type de bénéficiaire ('.$typefournisseur.', '.$typefournisseur.')'); //updateLog($username, $idcust, $action='' )
		header('location:typefournisseur.php?selectedTab=par&rs=2');
		break;

	case 'check':

		$msg = "";
		(isset($_POST['codetypefournisseur']) && $_POST['codetypefournisseur']!='' 		? $codetypefournisseur = trim($_POST['codetypefournisseur']) 	: $codetypedotation = '');

		if($codetypefournisseur !=''){
			$sql = "SELECT COUNT(CODE_TYPEFOUR) AS NBRE FROM  `typefournisseur` WHERE `CODE_TYPEFOUR` LIKE '".addslashes($codetypefournisseur)."'";
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

			if($row['NBRE']>0) {$msg ='<BR><img src="../images/alarm_un.gif" width="16" height="16" align="absmiddle"> Ce code existe d&eacute;j&agrave;, veuillez entrer un autre code type de<?php echo getlang(16); ?>.';}
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
		$sql = "SELECT * FROM  `typefournisseur` WHERE `CODE_TYPEFOUR` LIKE '".addslashes($split[0])."'";
		$query =  $cnx->prepare($sql); //Prepare the SQL
		$query->execute(); //Execute prepared SQL => $query
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$_SESSION['DATA_TBE'] = $row;
		header('location:edittypefournisseur.php?selectedTab=par&rs=2');
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
			$sql = "DELETE FROM  `typefournisseur` WHERE `CODE_TYPEFOUR` LIKE '".addslashes($split[0])."'";
			$query =  $cnx->prepare($sql); //Prepare the SQL
			$query->execute(); //Execute prepared SQL => $query
		}
		header('location:typefournisseur.php?selectedTab=par&rs=4');
		break;

	default : ///Nothing
		//header('location:../index.php');

}

if($myaction =='' && $do =='') header('location:../index.php');

?>
